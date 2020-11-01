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

namespace Traff\Registry\Logger;

/**
 * Class LineFormatter.
 *
 * @package Traff\Registry\Logger
 */
class LineFormatter extends \Monolog\Formatter\LineFormatter
{
    public function __construct(
        ?string $format = null,
        ?string $dateFormat = null,
        bool $allowInlineLineBreaks = false,
        bool $ignoreEmptyContextAndExtra = true
    ) {
        parent::__construct($format, $dateFormat, $allowInlineLineBreaks, $ignoreEmptyContextAndExtra);
    }

    public function format(array $record): string
    {
        foreach ($record['context'] as $key => $value) {
            $placeholder = \sprintf('{%s}', $key);
            if (false !== $record['message']) {
                $record['message'] = \str_replace($placeholder, $value, $record['message']);
                unset($record['context'][$key]);
            }
        }

        return parent::format($record);
    }
}
