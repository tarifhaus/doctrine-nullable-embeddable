<?php

declare(strict_types=1);

namespace Tarifhaus\Doctrine\ORM\NullableEmbeddable;

interface NullatorInterface
{
    public function setNull(&$object, $property);
}
