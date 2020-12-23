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

use Amp\Http\Client\DelegateHttpClient;
use Amp\Http\Client\Request;
use Amp\Http\Client\Response;
use Amp\NullCancellationToken;
use Amp\Promise;
use Psr\Log\LoggerInterface;

use function Amp\call;

/**
 * Class Registry.
 *
 */
final class RegistryClient implements Client
{
    public const VERSION = 'v2';

    private const HEADERS = [
        'Accept' => 'application/vnd.docker.distribution.manifest.v2+json',
    ];

    private ?Request $last_request;

    /**
     * Registry constructor.
     *
     * @param string                              $url    Registry URL.
     * @param \Amp\Http\Client\DelegateHttpClient $client
     * @param \Psr\Log\LoggerInterface            $logger Logger.
     */
    public function __construct(
        private string $url,
        private DelegateHttpClient $client,
        private LoggerInterface $logger,
    ) {}

    public function version(): string
    {
        return self::VERSION;
    }

    public function request(string $path, string $method, array $headers = []): Promise
    {
        return call(
            function () use ($path, $method, $headers): \Generator {
                $request = new Request($this->getUrl($path), $method);

                $this->logger->debug(
                    'Send request',
                    [
                        'url' => $request->getUri(),
                        'method' => $method,
                    ]
                );

                $headers = \array_merge(self::HEADERS, $headers);

                foreach ($headers as $name => $value) {
                    $request->addHeader($name, $value);
                }

                $this->logger->debug('With headers', $request->getHeaders());

                $response = yield $this->client->request($request, new NullCancellationToken());

                $this->last_request = $response->getRequest();

                $this->logger->debug(
                    'Response is ready',
                    [
                        'status' => $response->getStatus(),
                        'headers'=> $response->getHeaders()
                    ]
                );

                return $response;
            }
        );
    }

    public function dispatch(Response $response): Promise
    {
        return call(
            function () use (&$response): \Generator {
                return yield from $this->handleResponse($response);
            }
        );
    }

    public function getLastRequest(): ?Request
    {
        return $this->last_request;
    }

    /**
     * Handle registry response.
     *
     * @param \Amp\Http\Client\Response $response
     *
     * @throws \JsonException
     * @return \Generator
     */
    private function handleResponse(Response $response): \Generator
    {
        $body = (string) (yield $response->getBody()->buffer());

        if (405 === $response->getStatus()) {
            throw new \Error(
                'Method not allowed. May be you need to allow your registry to delete tags: '
                . 'see https://docs.docker.com/registry/configuration/#delete'
            );
        }

        if ($response->getStatus() >= 400) {
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
        return ! empty($body) ? \json_decode($body, true, 512, JSON_THROW_ON_ERROR) : [];
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
