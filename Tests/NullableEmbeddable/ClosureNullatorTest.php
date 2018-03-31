<?php

declare(strict_types=1);

namespace Tarifhaus\Doctrine\Tests\NullableEmbeddable;

use PHPUnit\Framework\TestCase;
use Tarifhaus\Doctrine\ORM\NullableEmbeddableListenerFactory;
use Tarifhaus\Doctrine\ORM\NullableEmbeddableListener;
use Tarifhaus\Doctrine\Tests\EntityWithoutSetter;
use Tarifhaus\Tests\Doctrine\ORM\NullableEmbeddable;

/**
 * @covers \Tarifhaus\Doctrine\ORM\NullableEmbeddable\ClosureNullator
 * @covers \Tarifhaus\Doctrine\ORM\NullableEmbeddableListenerFactory
 */
final class ClosureNullatorTest extends TestCase
{
    /**
     * @var NullableEmbeddableListener
     */
    private $listener;

    protected function setUp()
    {
        $this->listener = NullableEmbeddableListenerFactory::createWithClosureNullator();
    }

    public function test_it_nullates_without_using_setter()
    {
        $embeddable = new NullableEmbeddable(true);
        $object = new EntityWithoutSetter($embeddable);

        $this->listener->addMapping(get_class($object), 'property');
        $this->listener->postLoad($object);

        static::assertNull($object->getProperty());
    }
}
