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

use Traff\Registry\ImageTag;
use Mockery as m;

/**
 * Class ImageTagTest.
 *
 * @package Traff\Registry\Tests\Unit
 */
class ImageTagTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    /**
     * Get digest.
     *
     * @covers \Traff\Registry\ImageTag::getDigest
     *
     * @return void
     */
    public function testGetDigest(): void
    {
        $tag = new ImageTag('tag', 'digest');

        self::assertSame('digest', $tag->getDigest());
    }

    /**
     * To string  representation.
     *
     * @covers \Traff\Registry\ImageTag
     *
     * @return void
     */
    public function testToString(): void
    {
        $tag = new ImageTag('tag');

        self::assertSame('tag', (string) $tag);
    }

    /**
     * Get name.
     *
     * @covers \Traff\Registry\ImageTag::getName
     *
     * @return void
     */
    public function testGetName(): void
    {
        $tag = new ImageTag('tag');

        self::assertSame('tag', $tag->getName());
    }

    /**
     * With digest.
     *
     * @covers \Traff\Registry\ImageTag::withDigest
     * @uses \Traff\Registry\ImageTag::getDigest()
     *
     * @return void
     */
    public function testWithDigest(): void
    {
        $tag = new ImageTag('tag');
        $tag_with_digest = $tag->withDigest('digest');

        self::assertNull($tag->getDigest());
        self::assertSame('digest', $tag_with_digest->getDigest());
    }
}
