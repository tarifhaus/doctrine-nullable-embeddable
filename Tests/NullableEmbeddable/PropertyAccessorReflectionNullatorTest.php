<?php

declare(strict_types=1);

namespace Tarifhaus\Doctrine\Tests\NullableEmbeddable;

use PHPUnit\Framework\TestCase;
use Tarifhaus\Doctrine\ORM\NullableEmbeddableListenerFactory;
use Tarifhaus\Doctrine\ORM\NullableEmbeddableListener;
use Tarifhaus\Doctrine\Tests\EntityWithoutSetter;
use Tarifhaus\Doctrine\Tests\TopLevelEntityWithoutSetter;
use Tarifhaus\Tests\Doctrine\ORM\NullableEmbeddable;

/**
 * @covers \Tarifhaus\Doctrine\ORM\NullableEmbeddable\PropertyAccessorReflectionNullator
 */
final class PropertyAccessorReflectionNullatorTest extends TestCase
{
    /**
     * @var NullableEmbeddableListener
     */
    private $listener;

    protected function setUp()
    {
        $this->listener = NullableEmbeddableListenerFactory::createWithPropertyAccessorReflectionNullator();
    }

    public function test_it_nullates_without_using_setter()
    {
        $embeddable = new NullableEmbeddable(true);
        $object = new EntityWithoutSetter($embeddable);

        $this->listener->addMapping(get_class($object), 'property');
        $this->listener->postLoad($object);

        static::assertNull($object->getProperty());
    }

    public function test_it_nullates_nested_embeddables_without_using_setter()
    {
        $embeddable = new NullableEmbeddable(true);
        $childEntity = new EntityWithoutSetter($embeddable);
        $entity = new TopLevelEntityWithoutSetter($childEntity);

        $this->listener->addMapping(get_class($entity), 'childEntity.property');
        $this->listener->postLoad($entity);

        static::assertNull($entity->getChildEntity()->getProperty());
    }
}
