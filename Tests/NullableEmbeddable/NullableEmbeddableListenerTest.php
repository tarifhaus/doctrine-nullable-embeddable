<?php

declare(strict_types=1);

namespace Tarifhaus\Tests\Doctrine\ORM\NullableEmbeddable;

use PHPUnit\Framework\TestCase;
use Tarifhaus\Doctrine\ORM\NullableEmbeddableListenerFactory;
use Tarifhaus\Doctrine\ORM\NullableEmbeddableListener;
use Tarifhaus\Tests\Doctrine\ORM\NullableEmbeddable;

/**
 * @covers \Tarifhaus\Doctrine\ORM\NullableEmbeddable\PropertyAccessor
 * @covers \Tarifhaus\Doctrine\ORM\NullableEmbeddableListener
 * @covers \Tarifhaus\Doctrine\ORM\NullableEmbeddableListenerFactory
 */
final class NullableEmbeddableListenerTest extends TestCase
{
    /**
     * @var NullableEmbeddableListener
     */
    private $listener;

    protected function setUp()
    {
        $this->listener = NullableEmbeddableListenerFactory::createWithPropertyAccessor();
    }

    public function test_it_replaces_nullable_embeddable_with_null()
    {
        $embeddable = new NullableEmbeddable(true);

        $object = new \stdClass();
        $object->property = $embeddable;

        $this->listener->addMapping(get_class($object), 'property');
        $this->listener->postLoad($object);

        static::assertNull($object->property);
    }

    public function test_it_does_not_replace_values()
    {
        $embeddable = new NullableEmbeddable(false);

        $object = new \stdClass();
        $object->property = $embeddable;

        $this->listener->addMapping(get_class($object), 'property');
        $this->listener->postLoad($object);

        static::assertSame($embeddable, $object->property);
    }

    public function test_it_does_not_alter_unmapped_properties()
    {
        $embeddable = new \stdClass();

        $object = new \stdClass();
        $object->property = $embeddable;

        $this->listener->postLoad($object);

        static::assertSame($embeddable, $object->property);
    }

    public function test_it_does_not_alter_unsupported_values()
    {
        $embeddable = new \stdClass();

        $object = new \stdClass();
        $object->property = $embeddable;

        $this->listener->addMapping(get_class($object), 'property');
        $this->listener->postLoad($object);

        static::assertSame($embeddable, $object->property);
    }
}
