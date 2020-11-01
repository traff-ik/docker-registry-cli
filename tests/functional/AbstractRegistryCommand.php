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

use Amp\ByteStream;
use Amp\PHPUnit\AsyncTestCase;
use Amp\Process\Process;

/**
 * Class AbstractRegistryCommand.
 *
 * @package Traff\Registry\Tests\Functional
 */
abstract class AbstractRegistryCommand extends AsyncTestCase
{
    protected const REGISTRY_URL = 'http://registry:5000';

    protected const REGISTRY = 'registry:5000/';

    private $cwd;

    public function setUpAsync(): void
    {
        parent::setUpAsync();

        $this->cwd = \dirname(__DIR__, 2);
    }

    protected function getCwd(): string
    {
        return $this->cwd;
    }

    protected function createProcess(...$args): Process
    {
        return new Process(\sprintf('./bin/registry %s', \implode(' ', $args)), $this->getCwd());
    }

    public function getStdout(Process $process): \Generator
    {
        return yield ByteStream\buffer($process->getStdout());
    }

    public function getStderr(Process $process): \Generator
    {
        return yield ByteStream\buffer($process->getStderr());
    }
}
