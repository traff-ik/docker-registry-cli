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

use Amp\Promise;

/**
 * Interface RegistryInterface.
 *
 * @package Traff\Registry
 */
interface Client
{
    public function version(): string;

    public function request(string $path, string $method): Promise;
}
