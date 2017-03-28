<?php

declare(strict_types=1);

namespace Tarifhaus\Doctrine\ORM;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * @see https://github.com/doctrine/doctrine2/issues/4568
 * @see https://github.com/doctrine/doctrine2/pull/1275
 */
final class NullableEmbeddableListener
{
    private $propertyAccessor;
    private $propertyMap = [];

    public function __construct(PropertyAccessorInterface $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;
    }

    public function addMapping(string $entity, string $propertyPath)
    {
        if (empty($this->propertyMap[$entity])) {
            $this->propertyMap[$entity] = [];
        }

        $this->propertyMap[$entity][] = $propertyPath;
    }

    public function postLoad($object)
    {
        if (empty($this->propertyMap[get_class($object)])) {
            return;
        }

        $propertyPaths = $this->propertyMap[get_class($object)];
        foreach ($propertyPaths as $propertyPath) {
            $embeddable = $this->propertyAccessor->getValue($object, $propertyPath);
            if (!$embeddable instanceof NullableEmbeddableInterface) {
                continue;
            }

            if ($embeddable->isNull()) {
                $this->propertyAccessor->setValue($object, $propertyPath, null);
            }
        }
    }
}
