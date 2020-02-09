<?php

declare(strict_types=1);

namespace Tarifhaus\Doctrine\ORM;

use Tarifhaus\Doctrine\ORM\NullableEmbeddable\ClosureNullator;
use Tarifhaus\Doctrine\ORM\NullableEmbeddable\PropertyAccessor;
use Tarifhaus\Doctrine\ORM\NullableEmbeddable\PropertyAccessorReflectionNullator;

final class NullableEmbeddableListenerFactory
{
    public static function createWithPropertyAccessor(): NullableEmbeddableListener
    {
        $default = PropertyAccessor::createWithDefault();

        return new NullableEmbeddableListener($default, $default);
    }

    public static function createWithClosureNullator(): NullableEmbeddableListener
    {
        $evaluator = PropertyAccessor::createWithDefault();
        $nullator = new ClosureNullator();

        return new NullableEmbeddableListener($evaluator, $nullator);
    }

    public static function createWithPropertyAccessorReflectionNullator(): NullableEmbeddableListener
    {
        $evaluator = PropertyAccessor::createWithDefault();
        $nullator = PropertyAccessorReflectionNullator::createWithDefault();

        return new NullableEmbeddableListener($evaluator, $nullator);
    }
}
