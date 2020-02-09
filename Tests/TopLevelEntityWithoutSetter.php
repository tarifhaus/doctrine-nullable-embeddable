<?php

declare(strict_types=1);

namespace Tarifhaus\Doctrine\Tests;

final class TopLevelEntityWithoutSetter
{
    private $childEntity;

    public function __construct(EntityWithoutSetter $childEntity)
    {
        $this->childEntity = $childEntity;
    }

    public function getChildEntity()
    {
        return $this->childEntity;
    }
}
