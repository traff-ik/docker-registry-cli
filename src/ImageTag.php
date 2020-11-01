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

use Traff\Registry\Interfaces\ImageTagInterface;

/**
 * Class ImageTag.
 *
 * @package Traff\Registry
 */
final class ImageTag implements ImageTagInterface
{
    public const DEFAULT_TAG_NAME = 'latest';

    private ?string $digest;

    private string $name;

    public function __construct(string $name, ?string $digest = null)
    {
        $this->name = $name;
        $this->digest = $digest;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setDigest(string $digest): ImageTagInterface
    {
        $this->digest = $digest;
        return $this;
    }

    public function getDigest(): ?string
    {
        return $this->digest;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
