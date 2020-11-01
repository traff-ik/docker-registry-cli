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
 * Class RegistryVerboseTest.
 *
 * @package Traff\Registry\Tests\Functional
 */
class RegistryVerboseTest extends AbstractRegistryCommand
{
    public function testVerbose(): \Generator
    {
        $process = $this->createProcess('-r', self::REGISTRY_URL, '-v');

        yield $process->start();

        $stdout = yield from $this->getStdout($process);

        yield $process->join();

        self::assertStringContainsString('[DEBUG]', $stdout);
    }

    public function testNoVerbose(): \Generator
    {
        $process = $this->createProcess('-r', self::REGISTRY_URL);

        yield $process->start();

        $stdout = yield from $this->getStdout($process);

        yield $process->join();

        self::assertStringNotContainsString('[DEBUG]', $stdout);
    }
}
