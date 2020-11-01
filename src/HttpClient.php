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

namespace Traff\Registry;

use Amp\Http\Client\DelegateHttpClient;
use Amp\Http\Client\Request;
use Amp\NullCancellationToken;
use Amp\Promise;

use function Amp\call;

/**
 * Class Http.
 *
 */
final class HttpClient
{
    private const HEADERS = [
        'Accept' => 'application/vnd.docker.distribution.manifest.v2+json',
    ];

    private DelegateHttpClient $client;

    public function __construct(DelegateHttpClient $client)
    {
        $this->client = $client;
    }

    public function send(string $url, string $method, array $headers = []): Promise
    {
        return call(
            function () use ($url, $method, $headers): \Generator {
                $headers = \array_merge(self::HEADERS, $headers);
                $request = new Request($url, $method);

                if (! empty($headers)) {
                    foreach ($headers as $name => $value) {
                        $request->addHeader($name, $value);
                    }
                }

                return yield $this->client->request($request, new NullCancellationToken());
            }
        );
    }
}
