<?php

/**
 * Created by IntelliJ IDEA.
 *
 * PHP version 7.3
 *
 * @category docker-registry-cli
 * @author   Oleg Tikhonov <to@toro.one>
 */

declare(strict_types=1);

namespace Traff\Registry\Tests;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

/**
 * Trait MockeryTrait.
 *
 */
trait MockeryTrait
{
    use MockeryPHPUnitIntegration;

    /** @inheritDoc */
    protected function tearDown(): void
    {
        \Mockery::close();
    }

    /** @inheritDoc */
    protected function tearDownAsync(): void
    {
        \Mockery::close();
    }
}
