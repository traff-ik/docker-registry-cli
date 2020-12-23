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

use Traff\Registry\Logger\Traits\Formatter;

/**
 * Class LineFormatter.
 *
 * @package Traff\Registry\Logger
 */
class LineFormatter extends \Monolog\Formatter\LineFormatter
{
    use Formatter;

    /** @inheritDoc */
    public function __construct(
        ?string $format = null,
        ?string $dateFormat = null,
        bool $allowInlineLineBreaks = false,
        bool $ignoreEmptyContextAndExtra = true
    ) {
        parent::__construct($format, $dateFormat, $allowInlineLineBreaks, $ignoreEmptyContextAndExtra);
    }

    /** @inheritDoc */
    public function format(array $record): string
    {
        return parent::format($this->replacePlaceholders($record));
    }
}
