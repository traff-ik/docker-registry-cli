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
 * Interface ImageTagInterface.
 *
 * @package Traff\Registry
 */
interface TagInterface extends \Stringable
{
    public const DEFAULT_NAME = 'latest';

    public function getName(): string;

    public function getDigest(): Promise;

    public function delete(): Promise;
}
