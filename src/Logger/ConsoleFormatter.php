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

namespace Traff\Registry\Logger;

use Psr\Log\LogLevel;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Traff\Registry\Logger\Traits\Formatter;

use function Amp\Log\hasColorSupport;

/**
 * Class ConsoleFormatter.
 *
 * @package Traff\Registry\Logger
 */
class ConsoleFormatter extends \Monolog\Formatter\LineFormatter
{
    use Formatter;

    public const DEFAULT_FORMAT = "[%datetime%][%channel%][%level_name%] %message% %context% %extra%\r\n";

    /** @inheritDoc */
    public function __construct(
        ?string $format = null,
        ?string $dateFormat = null,
        private ?OutputFormatterInterface $formatter = null,
        bool $allowInlineLineBreaks = false,
        bool $ignoreEmptyContextAndExtra = true
    ) {
        parent::__construct($format ?? self::DEFAULT_FORMAT, $dateFormat, $allowInlineLineBreaks, $ignoreEmptyContextAndExtra);

        $this->formatter ??= new OutputFormatter(hasColorSupport(), ['debug' => new OutputFormatterStyle('cyan', '', ['bold'])]);
    }

    /** @inheritDoc */
    public function format(array $record): string
    {
        $record = $this->replacePlaceholders($record);

        $record['level_name'] = $this->formatter->format($this->colorLevel($record['level_name']));
        $record['channel'] = $this->formatter->format(\sprintf('<options=bold>%s</>', $record['channel']));
        $record['message'] = $this->formatter->format($record['message']);

        return parent::format($record);
    }

    private function colorLevel(string $level): string
    {
        return match (\strtolower($level)) {
            LogLevel::ERROR, LogLevel::EMERGENCY, LogLevel::CRITICAL, LogLevel::ALERT => "<options=bold;fg=red>{$level}</>",
            LogLevel::WARNING, LogLevel::NOTICE => "<options=bold;fg=yellow>{$level}</>",
            LogLevel::DEBUG => "<options=bold;fg=magenta>{$level}</>",
            default => "<options=bold>{$level}</>",
        };
    }
}
