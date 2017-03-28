<?php

declare(strict_types=1);

namespace Tarifhaus\Tests\Doctrine\ORM;

use Tarifhaus\Doctrine\ORM\NullableEmbeddableInterface;

final class NullableEmbeddable implements NullableEmbeddableInterface
{
    private $isNull;

    public function __construct(bool $isNull)
    {
        $this->isNull = $isNull;
    }

    public function isNull(): bool
    {
        return $this->isNull;
    }
}
