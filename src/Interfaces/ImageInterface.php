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

namespace Traff\Registry\Interfaces;

/**
 * Interface ImageInterface.
 *
 * @package Traff\Registry\Interfaces
 */
interface ImageInterface
{
    public function getName(): string;

    public function getTag(): ?ImageTagInterface;

    public function withTag(ImageTagInterface $tag): self;

    public function __toString(): string;
}
