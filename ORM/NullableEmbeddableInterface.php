<?php

declare(strict_types=1);

namespace Tarifhaus\Doctrine\ORM;

interface NullableEmbeddableInterface
{
    public function isNull(): bool;
}
