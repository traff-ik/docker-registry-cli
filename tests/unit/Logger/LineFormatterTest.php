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

namespace Traff\Registry\Tests\Unit\Logger;

use Monolog\Handler\TestHandler;
use Traff\Registry\Logger\LineFormatter;

/**
 * Class LineFormatterTest.
 *
 * @package Traff\Registry\Tests\Unit\Logger
 */
class LineFormatterTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    /**
     * Format log message.
     *
     * @covers \Traff\Registry\Logger\LineFormatter
     * @covers \Traff\Registry\Logger\LineFormatter::format
     *
     * @return void
     */
    public function testFormat(): void
    {
        $formatter = new LineFormatter('%channel%.%level_name% %message% %context%');

        $handler = new TestHandler();
        $handler->setFormatter($formatter);
        $logger = new \Monolog\Logger('unittest', [$handler]);
        $context = ['context' => 'context', 'injection' => 'text'];

        $logger->info('formatted message with {injection}', $context);

        unset($context['injection']);
        foreach ($handler->getRecords() as $record) {
            /** @noinspection JsonEncodingApiUsageInspection */
            self::assertStringContainsString('unittest.INFO formatted message with text ' . \json_encode($context), $record['formatted']);
        }
    }
}
