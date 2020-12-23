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

namespace Traff\Registry\Tag {
    function name(string $image_name): array {
        $tag_name = null;

        if (\str_contains($image_name, ':')) {
            [$image_name, $tag_name] = \explode(':', $image_name, 2);
        }

        return [$image_name, $tag_name];
    }
}
