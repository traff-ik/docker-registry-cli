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
 * Interface ImageTagInterface.
 *
 * @package Traff\Registry\Interfaces
 */
interface ImageTagInterface
{
    public const DEFAULT_TAG_NAME = 'latest';

    /**
     * Return tag name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Return an instance with specified tag digest.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified tag digest.
     *
     * @param string $digest Tag digest from registry.
     *
     * @return $this
     */
    public function withDigest(string $digest): self;

    /**
     * Return specified tag digest.
     *
     * @return string|null
     */
    public function getDigest(): ?string;

    /**
     * Tag to string representation.
     *
     * @return string
     */
    public function __toString(): string;
}
