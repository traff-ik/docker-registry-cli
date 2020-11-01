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
 * Interface ImageTagInterface.
 *
 * @package Traff\Registry\Interfaces
 */
interface ImageTagInterface
{
    public function getName(): string;

    public function setDigest(string $digest): self;

    public function getDigest(): ?string;

    public function __toString(): string;
}
