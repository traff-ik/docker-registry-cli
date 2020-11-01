<?php

/**
 * Created by IntelliJ IDEA.
 *
 * PHP version 7.4
 *
 * @category docker-registry-cli
 * @author   Oleg Tikhonov <o.tikhonov@nexta.pro>
 */

/** @noinspection SpellCheckingInspection */

declare(strict_types=1);

namespace Traff\Registry\Tests\Unit\Logger;

use League\CLImate\Logger;
use Monolog\Formatter\LineFormatter;
use Psr\Log\LogLevel;
use Traff\Registry\Logger\ClImateHandler;
use Mockery as m;

/**
 * Class ClImateHandlerTest.
 *
 * @package Traff\Registry\Tests\Unit\Logger
 */
class ClImateHandlerTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    /**
     * Logger handler.
     *
     * @covers \Traff\Registry\Logger\ClImateHandler
     * @covers \Traff\Registry\Logger\ClImateHandler::write
     * @covers \Traff\Registry\Logger\ClImateHandler::convertLogLevel
     *
     * @return void
     */
    public function testLog(): void
    {
        $cli_logger = m::mock(Logger::class);
        $handler = new ClImateHandler($cli_logger);
        $formatter = new LineFormatter('%channel% %level_name% %message%');
        $handler->setFormatter($formatter);
        $logger = new \Monolog\Logger('unittest', [$handler]);
        $context = ['context' => 'context'];

        $cli_logger->expects('log')->with(LogLevel::DEBUG, 'unittest DEBUG debug message');

        $logger->debug('debug message', $context);
    }
}
