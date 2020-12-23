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

namespace Traff\Registry\Command\Traits;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\OutputInterface;
use Traff\Registry\LoggerBuilder;

/**
 * Trait Command.
 *
 * @package Traff\Registry\Traits
 */
trait Command
{
    private function logger(OutputInterface $output): LoggerInterface
    {
        return (new LoggerBuilder())->level($output->isVerbose() ? LogLevel::DEBUG : LogLevel::INFO)->build();
    }

    private function tagName(string $name): array
    {
        return \Traff\Registry\Tag\name($name);
    }
}
