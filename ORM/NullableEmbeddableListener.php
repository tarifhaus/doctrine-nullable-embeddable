<?php

declare(strict_types=1);

namespace Tarifhaus\Doctrine\ORM;

use Tarifhaus\Doctrine\ORM\NullableEmbeddable\NullatorInterface;
use Tarifhaus\Doctrine\ORM\NullableEmbeddable\EvaluatorInterface;

/**
 * @see https://github.com/doctrine/doctrine2/issues/4568
 * @see https://github.com/doctrine/doctrine2/pull/1275
 */
final class NullableEmbeddableListener
{
    private $evaluator;
    private $nullator;

    /**
     * @var string[][]
     */
    private $propertyMap = [];

    public function __construct(EvaluatorInterface $evaluator, NullatorInterface $nullator)
    {
        $this->evaluator = $evaluator;
        $this->nullator = $nullator;
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
        $entity = get_class($object);
        if (empty($this->propertyMap[$entity])) {
            return;
        }

        $entries = $this->propertyMap[$entity];
        foreach ($entries as $property) {
            if ($this->evaluator->isNull($object, $property)) {
                $this->nullator->setNull($object, $property);
            }
        }
    }
}
