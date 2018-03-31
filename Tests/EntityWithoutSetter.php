<?php

declare(strict_types=1);

namespace Tarifhaus\Doctrine\Tests;

use Tarifhaus\Doctrine\ORM\NullableEmbeddableInterface;

final class EntityWithoutSetter
{
    private $property;

    public function __construct(NullableEmbeddableInterface $embeddable)
    {
        $this->property = $embeddable;
    }

    public function getProperty()
    {
        return $this->property;
    }
}
