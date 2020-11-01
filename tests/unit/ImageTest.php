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

namespace Traff\Registry\Tests\Unit;

use Traff\Registry\Image;
use Mockery as m;
use Traff\Registry\ImageTagInterface;

/**
 * Class ImageTest.
 *
 * @package Traff\Registry\Tests\Unit
 */
class ImageTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    /**
     * Test image with tag.
     *
     * @covers \Traff\Registry\Image::withTag
     * @uses \Traff\Registry\Image::getTag()
     *
     * @return void
     */
    public function testWithTag(): void
    {
        $tag_mock = m::mock(ImageTagInterface::class);
        $tag_mock->allows(['getName' => 'tag']);

        $image = new Image('image');

        $image_with_tag = $image->withTag($tag_mock);

        self::assertNull($image->getTag());
        self::assertSame('tag', $image_with_tag->getTag()->getName());
    }

    /**
     * Image get name.
     *
     * @covers \Traff\Registry\Image::getName
     *
     * @return void
     */
    public function testGetName(): void
    {
        $image = new Image('image');

        self::assertSame('image', $image->getName());
    }

    /**
     * Image can converts to string.
     *
     * @covers \Traff\Registry\Image
     *
     * @return void
     */
    public function testToString(): void
    {
        $image = new Image('image');

        self::assertSame('image', (string) $image);

        $tag_mock = m::mock(ImageTagInterface::class);
        $tag_mock->expects('__toString')->andReturn('tag');

        $image = new Image('image', $tag_mock);

        self::assertSame('image:tag', (string) $image);
    }

    /**
     * Image get tag.
     *
     * @covers \Traff\Registry\Image::getTag
     *
     * @return void
     */
    public function testGetTag(): void
    {
        $image = new Image('image');

        self::assertNull($image->getTag());

        $tag_mock = m::mock(ImageTagInterface::class);
        $image = new Image('image', $tag_mock);

        self::assertSame($tag_mock, $image->getTag());
    }
}
