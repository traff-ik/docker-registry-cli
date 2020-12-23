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

namespace Traff\Registry;

use Amp\ByteStream\ResourceOutputStream;
use Amp\Log\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Traff\Registry\Logger\ConsoleFormatter;

/**
 * Class LoggerBuilder.
 *
 * @package Traff\Registry
 */
final class LoggerBuilder
{
    public const DEFAULT_NAME = 'docker-registry';

    private $stdout;

    private $stderr;

    private $log_level = LogLevel::INFO;

    private $name = self::DEFAULT_NAME;

    public function build(): LoggerInterface
    {
        $logger = new Logger($this->name);

        $log_handler = new StreamHandler(new ResourceOutputStream($this->stdout ?? \STDOUT), $this->log_level);
        $log_handler->setFormatter(new ConsoleFormatter());
        $logger->pushHandler($log_handler);

        return $logger;
    }

    public function name(string $name): self
    {
        $new = clone $this;
        $new->name = $name;

        return $new;
    }

    public function level(string $level): self
    {
        if (empty($level)) {
            throw new \InvalidArgumentException('Log level can not be empty');
        }

        $new = clone $this;
        $new->log_level = $level;

        return $new;
    }

    public function withStdOut($stream): self
    {
        if (!\is_resource($stream) || 'stream' !== \get_resource_type($stream)) {
            throw new \InvalidArgumentException('Stream is required');
        }

        $new = clone $this;
        $new->stdout = $stream;

        return $new;
    }

    public function withStdErr($stream): self
    {
        if (!\is_resource($stream) || 'stream' !== \get_resource_type($stream)) {
            throw new \InvalidArgumentException('Stream is required');
        }

        $new = clone $this;
        $new->stderr = $stream;

        return $new;
    }
}
