<?php

declare(strict_types=1);

namespace Tarifhaus\Doctrine\ORM\NullableEmbeddable;

use Symfony\Component\PropertyAccess\PropertyAccessor as DefaultPropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Tarifhaus\Doctrine\ORM\NullableEmbeddableInterface;

final class PropertyAccessor implements EvaluatorInterface, NullatorInterface
{
    private $propertyAccessor;

    public function __construct(PropertyAccessorInterface $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;
    }

    public static function createWithDefault()
    {
        return new self(new DefaultPropertyAccessor());
    }

    public function isNull($object, $property): bool
    {
        $embeddable = $this->propertyAccessor->getValue($object, $property);
        if ($embeddable instanceof NullableEmbeddableInterface) {
            return $embeddable->isNull();
        }

        return false;
    }

    public function setNull(&$object, $property)
    {
        $this->propertyAccessor->setValue($object, $property, null);
    }
}
