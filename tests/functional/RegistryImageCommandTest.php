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
 * Class RegistryImageCommandTest.
 *
 * @package Traff\Registry\Tests\Functional
 */
class RegistryImageCommandTest extends AbstractRegistryCommand
{
    /**
     * Test deleting the image tag.
     *
     * @return \Generator
     */
    public function testDeleteTag(): \Generator
    {
        $image_name = 'alpine:latest';
        $process = $this->createProcess('-r', self::REGISTRY_URL, '-i', $image_name, '-d', '-v');

        yield $process->start();

        $stdout = yield from $this->getStdout($process);

        yield $process->join();

        $contains = [
            '[INFO] Deleting the image alpine:latest',
            '[DEBUG] Getting digest',
            '[DEBUG] Got digest',
            '[INFO] Request was succeeded'
        ];

        foreach ($contains as $needle) {
            self::assertStringContainsString($needle, $stdout);
        }
    }

    /**
     * Test image tag not found while deleting it.
     *
     * @return \Generator
     */
    public function testDeleteTagNotFound(): \Generator
    {
        $image_name = 'alpine:not-found';
        $process = $this->createProcess('-r', self::REGISTRY_URL, '-i', $image_name, '-d', '-v');

        yield $process->start();

        $stderr = yield from $this->getStderr($process);

        yield $process->join();

        self::assertNotEmpty($stderr);
        self::assertStringContainsString('not found in the registry', $stderr);
    }
}
