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
 * Interface HttpClientInterface.
 *
 * @package Traff\Registry
 */
interface HttpClientInterface
{
    /**
     * Send request to the registry service.
     *
     * @param string $url     URL.
     * @param string $method  HTTP request method.
     * @param array  $headers HTTP request headers.
     *
     * @return \Amp\Promise<\Amp\Http\Client\Response>
     */
    public function send(string $url, string $method, array $headers = []): Promise;
}
