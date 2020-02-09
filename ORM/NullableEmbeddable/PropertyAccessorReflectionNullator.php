<?php

declare(strict_types=1);

namespace Tarifhaus\Doctrine\ORM\NullableEmbeddable;

use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccessor as DefaultPropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

final class PropertyAccessorReflectionNullator implements NullatorInterface
{
    private $propertyAccessor;
    private $closureNullator;

    public function __construct(PropertyAccessorInterface $propertyAccessor, ClosureNullator $closureNullator)
    {
        $this->propertyAccessor = $propertyAccessor;
        $this->closureNullator = $closureNullator;
    }

    public static function createWithDefault()
    {
        return new self(new DefaultPropertyAccessor(), new ClosureNullator());
    }

    public function setNull(&$object, $property)
    {
        try {
            $this->propertyAccessor->setValue($object, $property, null);
        } catch (NoSuchPropertyException $exception) {
            $this->setNullByReflection($object, $property);
        }
    }

    private function setNullByReflection(&$object, $property)
    {
        $entity = clone $object;

        $this->makePropertyPublic($object, $property);
        $this->closureNullator->setNull($object, $property);

        $object = $entity;
    }

    private function makePropertyPublic(&$object, $property)
    {
        $reflectionProperty = $this->getReflectionProperty($object, $property);

        if (false !== strpos($property, '.')) {
            $reflectionProperty = $this->getPropertyPathReflectionProperty($object, $property);
        }

        if (null === $reflectionProperty) {
            throw new NoSuchPropertyException();
        }

        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, null);
    }

    private function getPropertyPathReflectionProperty(&$object, $property)
    {
        $reflectionProperty = null;

        $propertyPathProperties = explode('.', $property);
        $numOfProperties = \count($propertyPathProperties);

        foreach ($propertyPathProperties as $propertyIndex => $pathProperty) {
            $reflectionProperty = $this->getReflectionProperty($object, $pathProperty);

            if ($propertyIndex < $numOfProperties - 1) {
                $object = $this->propertyAccessor->getValue($object, $pathProperty);
            }
        }

        return $reflectionProperty;
    }

    private function getReflectionProperty($object, $property)
    {
        if (false === \is_object($object)) {
            return null;
        }

        $reflectionClass = (new \ReflectionClass(\get_class($object)));

        while ($reflectionClass instanceof \ReflectionClass) {
            if ($reflectionClass->hasProperty($property)) {
                return $reflectionClass->getProperty($property);
            }

            $reflectionClass = $reflectionClass->getParentClass();
        }

        return null;
    }
}
