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

use Traff\Registry\Image;
use Traff\Registry\ImageTag;
use Traff\Registry\ImageInterface;
use Traff\Registry\ImageTagInterface;

/**
 * Class ImageFactory.
 *
 * @package Traff\Registry\Factory
 */
final class ImageFactory implements ImageFactoryInterface
{
    /**
     * Create image object.
     *
     * @param string      $image_name Image name.
     * @param string|null $tag        Image tag name.
     *
     * @return \Traff\Registry\ImageInterface
     */
    public function createImage(string $image_name, ?string $tag = null): ImageInterface
    {
        if (null === $tag && false !== \strpos($image_name, ':')) {
            [$image_name, $tag] = \explode(':', $image_name, 2);
        }

        return new Image($image_name, null !== $tag ? $this->createTag($tag) : null);
    }

    /**
     * Create image tag object.
     *
     * @param string      $name   Tag name.
     * @param string|null $digest Tag digest from the registry.
     *
     * @return \Traff\Registry\ImageTagInterface
     */
    public function createTag(string $name, ?string $digest = null): ImageTagInterface
    {
        return new ImageTag($name, $digest);
    }
}
