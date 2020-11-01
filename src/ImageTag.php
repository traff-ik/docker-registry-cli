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

    /**
     * ImageTag constructor.
     *
     * @param string      $name   Tag name.
     * @param string|null $digest Tag digest from registry.
     *
     */
    public function __construct(string $name, ?string $digest = null)
    {
        $this->name = $name;
        $this->digest = $digest;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function withDigest(string $digest): ImageTagInterface
    {
        $new = clone $this;
        $new->digest = $digest;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getDigest(): ?string
    {
        return $this->digest;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->name;
    }
}
