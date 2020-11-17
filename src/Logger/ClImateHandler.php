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

namespace Traff\Registry\Logger;

use League\CLImate\Logger as CLImateLogger;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;

/**
 * Class Logger.
 *
 * @package Traff\Registry\Logger
 */
class ClImateHandler extends AbstractProcessingHandler
{
    private const LEVELS = [
        Logger::EMERGENCY => LogLevel::EMERGENCY,
        Logger::ALERT => LogLevel::ALERT,
        Logger::CRITICAL => LogLevel::CRITICAL,
        Logger::ERROR => LogLevel::ERROR,
        Logger::WARNING => LogLevel::WARNING,
        Logger::NOTICE => LogLevel::NOTICE,
        Logger::INFO => LogLevel::INFO,
        Logger::DEBUG => LogLevel::DEBUG,
    ];

    private CLImateLogger $logger;

    /** @inheritDoc */
    public function __construct(CLImateLogger $logger, $level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->logger = $logger;
    }

    /** @inheritDoc */
    protected function write(array $record): void
    {
        $this->logger->log($this->convertLogLevel($record['level']), $record['formatted']);
    }

    /**
     * Converts monolog log level to the psr log level.
     *
     * @param int $level Monolog log level.
     *
     * @return string
     */
    private function convertLogLevel(int $level): string
    {
        if (! isset(self::LEVELS[$level])) {
            throw new InvalidArgumentException(\sprintf('Invalid log level %s', $level));
        }

        return self::LEVELS[$level];
    }
}
