<?php

declare(strict_types=1);

namespace Tarifhaus\Doctrine\ORM\NullableEmbeddable;

final class ClosureNullator implements NullatorInterface
{
    public function setNull(&$object, $property)
    {
        $nullator = \Closure::bind(function ($property) {
            $this->{$property} = null;
        }, $object, $object);

        $nullator($property);
    }
}
