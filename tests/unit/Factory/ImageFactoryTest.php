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

namespace Traff\Registry\Tests\Unit\Factory;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Traff\Registry\Factory\ImageFactory;

/**
 * Class ImageFactoryTest.
 *
 * @package Traff\Registry\Tests\Unit\Factory
 */
class ImageFactoryTest extends MockeryTestCase
{
    /**
     * Image factory creating tag.
     *
     * @covers \Traff\Registry\Factory\ImageFactory::createTag
     * @uses \Traff\Registry\ImageTag::getName()
     *
     * @return void
     */
    public function testCreateTag(): void
    {
        $factory = new ImageFactory();

        $tag = $factory->createTag('tag');

        self::assertSame('tag', $tag->getName());
    }

    /**
     * Image factory creating image with tag.
     *
     * @covers \Traff\Registry\Factory\ImageFactory::createImage
     * @uses \Traff\Registry\Image::getName()
     * @uses \Traff\Registry\Image::getTag()
     * @uses \Traff\Registry\ImageTag::getName()
     *
     * @return void
     */
    public function testCreateImage(): void
    {
        $factory = new ImageFactory();

        $image = $factory->createImage('image');

        self::assertSame('image', $image->getName());
        self::assertNull($image->getTag());

        $image = $factory->createImage('image', 'tag');

        self::assertSame('image', $image->getName());
        self::assertSame('tag', $image->getTag()->getName());

        $image = $factory->createImage('image:tag');

        self::assertSame('image', $image->getName());
        self::assertSame('tag', $image->getTag()->getName());
    }
}
