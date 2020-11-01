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

use Traff\Registry\Interfaces\ImageInterface;
use Traff\Registry\Interfaces\ImageTagInterface;

/**
 * Class Image.
 *
 */
final class Image implements ImageInterface
{
    private string $name;

    private ?ImageTagInterface $tag;

    public function __construct(string $name, ?ImageTagInterface $tag = null)
    {
        $this->name = $name;
        $this->tag = $tag;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTag(): ?ImageTagInterface
    {
        return $this->tag;
    }

    public function withTag(ImageTagInterface $tag): ImageInterface
    {
        $new = clone $this;
        $new->tag = $tag;

        return $new;
    }

    public function __toString(): string
    {
        return null !== $this->getTag() ? \sprintf('%s:%s', $this->getName(), $this->getTag()) : $this->getName();
    }
}
