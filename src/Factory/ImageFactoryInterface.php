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

namespace Traff\Registry\Factory;

use Traff\Registry\ImageInterface;
use Traff\Registry\ImageTagInterface;

/**
 * Interface ImageFactoryInterface.
 *
 * @package Traff\Registry\Interfaces
 */
interface ImageFactoryInterface
{
    public function createImage(string $image_name, ?string $tag = null): ImageInterface;

    public function createTag(string $name, ?string $digest = null): ImageTagInterface;
}
