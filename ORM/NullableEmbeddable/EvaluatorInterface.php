<?php

declare(strict_types=1);

namespace Tarifhaus\Doctrine\ORM\NullableEmbeddable;

interface EvaluatorInterface
{
    public function isNull($object, $property): bool;
}
