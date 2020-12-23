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
use Amp\Http\Client\HttpClientBuilder;
use Psr\Log\LoggerInterface;

/**
 * Class RegistryClientFactory.
 *
 * @package Traff\Registry
 */
final class RegistryClientFactory implements ClientFactory
{
    public function createClient(
        string $url,
        LoggerInterface $logger,
        ?DelegateHttpClient $http_client = null
    ): Client {
        $http_client ??= (new HttpClientBuilder())->build();

        return new RegistryClient($url, $http_client, $logger);
    }
}
