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

namespace Traff\Registry\Logger\Traits;

/**
 * Trait LineFormatterTrait.
 *
 * @package Traff\Registry\Logger
 */
trait Formatter
{
    private function replacePlaceholders(array $record): array
    {
        foreach ($record['context'] as $key => $value) {
            $placeholder = \sprintf('{%s}', $key);

            if (\str_contains($record['message'], $placeholder)) {
                $record['message'] = \str_replace($placeholder, $value, $record['message']);
                unset($record['context'][$key]);
            }
        }

        return $record;
    }
}
