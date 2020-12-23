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

/**
 * Class Image.
 *
 * @package Traff\Registry
 */
final class Image implements ImageInterface
{
    public function __construct(
        private string $name,
        private Client $client,
    ) {}

    public function __toString(): string
    {
        return $this->name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function createTag(?string $name): TagInterface
    {
        return new Tag($name, $this, $this->client);
    }
}
