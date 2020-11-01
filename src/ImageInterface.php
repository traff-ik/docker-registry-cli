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
 * Interface ImageInterface.
 *
 * @package Traff\Registry\Interfaces
 */
interface ImageInterface
{
    /**
     * Return image name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Return image tag if specified.
     *
     * @return \Traff\Registry\ImageTagInterface|null
     */
    public function getTag(): ?ImageTagInterface;

    /**
     * Return an instance with the specified tag.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified tag.
     *
     * @param \Traff\Registry\ImageTagInterface $tag
     *
     * @return $this
     */
    public function withTag(ImageTagInterface $tag): self;

    /**
     * Return an instance string representation.
     *
     * @return string
     */
    public function __toString(): string;
}
