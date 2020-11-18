<?php

/**
 * Created by IntelliJ IDEA.
 *
 * PHP version 7.4
 *
 * @category docker-registry-cli
 * @author   Oleg Tikhonov <o.tikhonov@nexta.pro>
 */

declare(strict_types=1);

namespace Traff\Registry;

use Amp\Http\Client\Response;
use Amp\Promise;
use Psr\Log\LoggerInterface;
use Traff\Registry\Factory\ImageFactoryInterface;

use function Amp\call;

/**
 * Class Registry.
 *
 */
final class Registry
{
    public const VERSION = 'v2';

    private const CODE_NOT_FOUND = 404;

    private const CODE_NOT_ALLOWED = 405;

    private const CODE_SUCCESS = 200;

    private string $url;

    private HttpClientInterface $http_client;

    private LoggerInterface $logger;

    private ?ImageFactoryInterface $image_factory;

    /**
     * Registry constructor.
     *
     * @param string                                        $url           Registry URL.
     * @param \Traff\Registry\HttpClientInterface           $http_client   HTTP-client.
     * @param \Psr\Log\LoggerInterface                      $logger        Logger.
     * @param \Traff\Registry\Factory\ImageFactoryInterface $image_factory Image object factory.
     */
    public function __construct(
        string $url,
        HttpClientInterface $http_client,
        LoggerInterface $logger,
        ImageFactoryInterface $image_factory
    ) {
        $this->http_client = $http_client;
        $this->url = $url;
        $this->image_factory = $image_factory;
        $this->logger = $logger;
    }

    /**
     * getTagDigest.
     *
     * @param string      $image_name
     * @param string|null $tag
     *
     * @return \Amp\Promise<\Traff\Registry\ImageInterface>
     */
    public function getTagDigest(string $image_name, ?string $tag = null): Promise
    {
        return call(
            function () use ($image_name, $tag): \Generator {
                $image = $this->image_factory->createImage($image_name, $tag);

                if (null === $image->getTag()) {
                    throw new \Error(\sprintf('Image tag must be provided for %s', $image));
                }

                $path = \sprintf('%s/manifests/%s', $image->getName(), $image->getTag());

                $this->logger->debug('Retrieving digest for the {image}', ['image' => $image, 'path' => $path]);

                $response = yield $this->http_client->send($this->getUrl($path), 'HEAD');

                try {
                    $this->handleResponse($response);
                } catch (\Error $e) {
                    if (self::CODE_NOT_FOUND === $e->getCode()) {
                        throw new \Error(\
                            sprintf('Image %s not found in the repository', $image), self::CODE_NOT_FOUND,
                            $e
                        );
                    }
                }

                $tag_digest = $response->getHeader('Docker-Content-Digest');

                if (empty($tag_digest)) {
                    throw new \Error(\sprintf('Tag digest not found for the %s', $image));
                }

                $this->logger->debug('Received digest "{digest}"', ['digest' => $tag_digest]);

                return $image->withTag($image->getTag()->withDigest($tag_digest));
            }
        );
    }

    /**
     * Delete image by tag.
     *
     * @param string      $image_name Image name.
     * @param string|null $tag        Tag name.
     *                                Tag name is "latest" by default if not provided.
     *
     * @return \Amp\Promise<\Traff\Registry\ImageInterface>
     */
    public function deleteTag(string $image_name, ?string $tag = null): Promise
    {
        return call(
            function () use ($image_name, $tag): \Generator {
                $image = yield $this->getTagDigest($image_name, $tag);

                $path = \sprintf('%s/manifests/%s', $image->getName(), $image->getTag()->getDigest());

                $this->logger->debug('Sending delete request {path}', ['path' => $path]);
                $response = yield $this->http_client->send($this->getUrl($path), 'DELETE');

                $this->handleResponse($response);

                $this->logger->info('Request was succeeded');

                return $image;
            }
        );
    }

    private function handleResponse(Response $response): \Generator
    {
        $body = (string) (yield $response->getBody()->buffer());

        if (self::CODE_NOT_ALLOWED === $response->getStatus()) {
            throw new \Error(
                'Method not allowed. May be you need to allow your registry to delete tags: '
                . 'see https://docs.docker.com/registry/configuration/#delete'
            );
        }

        if ($response->getStatus() >= 400) {
            throw new \Error($this->getResponseError($body), $response->getStatus());
        }

        if (self::CODE_SUCCESS !== $response->getStatus()) {
            throw new \Error($this->getResponseError($body), $response->getStatus());
        }

        return $this->getResponseBody($body);
    }

    /**
     * Format response error.
     *
     * @param string $body Response body.
     *
     * @throws \JsonException
     * @return string
     */
    private function getResponseError(string $body): string
    {
        return print_r($this->getResponseBody($body), true);
    }

    /**
     * Prepare response.
     *
     * @param string $body Response body.
     *
     * @throws \JsonException
     * @return array
     */
    private function getResponseBody(string $body): array
    {
        return \json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Return prepared URL.
     *
     * @param string $path Initial URL path.
     *
     * @return string
     */
    private function getUrl(string $path): string
    {
        return \sprintf('%s/%s/%s', \rtrim($this->url, '/'), self::VERSION, \rtrim($path, '/'));
    }
}
