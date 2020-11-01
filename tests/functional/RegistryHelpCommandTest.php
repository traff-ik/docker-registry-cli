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

namespace Traff\Registry\Tests\Functional;

/**
 * Class RegistryCommandTest.
 *
 * @package Traff\Registry\Tests\Functional
 */
class RegistryHelpCommandTest extends AbstractRegistryCommand
{
    public function testResponse(): \Generator
    {
        $process = $this->createProcess('--help');

        yield $process->start();

        $stderr = yield from $this->getStderr($process);
        $stdout = yield from $this->getStdout($process);

        yield $process->join();

        self::assertEmpty($stderr, $stderr);
        self::assertStringContainsString('Docker registry CLI', $stdout);
    }

    public function testResponseWithEmptyArg(): \Generator
    {
        $process = $this->createProcess('');

        yield $process->start();

        $stderr = yield from $this->getStderr($process);
        $stdout = yield from $this->getStdout($process);

        yield $process->join();

        self::assertEmpty($stderr, $stderr);
        self::assertStringContainsString('Docker registry CLI', $stdout);
    }
}
