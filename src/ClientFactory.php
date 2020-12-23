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

use Amp\Http\Client\DelegateHttpClient;
use Psr\Log\LoggerInterface;

/**
 * Interface ClientFactory.
 *
 * @package Traff\Registry
 */
interface ClientFactory
{
    public function createClient(
        string $url,
        LoggerInterface $logger,
        ?DelegateHttpClient $http_client = null
    ): Client;
}
