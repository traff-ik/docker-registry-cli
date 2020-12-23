<?php

/**
 * Created by IntelliJ IDEA.
 *
 * PHP version 7.3
 *
 * @category docker-registry-cli
 * @author   Oleg Tikhonov <to@toro.one>
 */

declare(strict_types=1);

namespace Traff\Registry;

use Amp\Promise;
use Amp\Success;
use Traff\Registry\Exception\TagDoesNotExistException;
use function Amp\call;

/**
 * Class Tag.
 *
 * @package Traff\Registry
 */
final class Tag implements TagInterface
{
    private ?string $digest = null;

    public function __construct(
        private ?string $name,
        private ImageInterface $image,
        private Client $client,
    ) {
        $this->name ??= self::DEFAULT_NAME;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImage(): ImageInterface
    {
        return $this->image;
    }

    public function getDigest(): Promise
    {
        if (null !== $this->digest) {
            return new Success($this->digest);
        }

        return call(
            function (): \Generator {
                $path = \sprintf('%s/manifests/%s', $this->image->getName(), $this->getName());
                $response = yield $this->client->request($path, 'HEAD');

                $tag_digest = $response->getHeader('Docker-Content-Digest');

                if (empty($tag_digest)) {
                    throw new TagDoesNotExistException(\sprintf('Tag digest not found for the %s', $this));
                }

                $this->digest = $tag_digest;

                return $tag_digest;
            }
        );
    }

    public function delete(): Promise
    {
        return call(
            function (): \Generator {
                $digest = yield $this->getDigest();

                $path = \sprintf('%s/manifests/%s', $this->image->getName(), $digest);
                $response = yield $this->client->request($path, 'DELETE');

                $this->digest = null;

                return yield $this->client->dispatch($response);
            }
        );
    }

    public function __toString(): string
    {
        return \sprintf('%s:%s', $this->image->getName(), $this->name);
    }
}
