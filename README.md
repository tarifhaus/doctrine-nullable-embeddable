# Doctrine: nullable embeddable

This listener enables you to have truly `null` values for your [Doctrine embeddables](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/tutorials/embeddables.html).
The listener hooks into the `postLoad` [lifecycle callback](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/events.html#postload) and replaces embeddable values that are null.

It implements a workaround for this open issue:

* https://github.com/doctrine/doctrine2/issues/4568
* https://github.com/doctrine/doctrine2/pull/1275

The listener depends on an `evaluator` to check whether to instruct a `nullator` to actually replace the embeddable with `null`.
This helper library ships with different implementations and respective interfaces to roll your own.

The default `evaluator` requires the `embeddable` class to implement the `Tarifhaus\Doctrine\ORM\NullableEmbeddableInterface`.
It contains a method `isNull(): bool` which tells the evaluator, whether the loaded embeddable should be treated as and therefore replaced with `null`.

The `nullator` actually replaces the embeddable with `null` in a specific way.

## Configuration

There are two things you have to do, in order to have this listener working correctly.

1. An entry to the property map has to be made.
   This is done by calling the `\Tarifhaus\Doctrine\ORM\NullableEmbeddableListener::addMapping`.
   It receives the FQCN (fully qualified class name) of the entity and the name of the property the embeddable resides in.

   For example:
   ```php
   <?php

   use Tarifhaus\Doctrine\ORM\NullableEmbeddableListenerFactory;

   $listener = NullableEmbeddableListenerFactory::createWithClosureNullator();
   $listener->addMapping('App\Domain\User\Model\UserProfile', 'address');
   ```

2. Now the listener needs to be registered with the Doctrine `EventManager`.
   ```php
   <?php

   use Doctrine\Common\EventManager;
   use Doctrine\ORM\Events;
   use Tarifhaus\Doctrine\ORM\NullableEmbeddableListenerFactory;

   $listener = NullableEmbeddableListenerFactory::createWithClosureNullator();
   $listener->addMapping('App\Domain\User\Model\UserProfile', 'address');

   $evm = new EventManager();
   $evm->addEventListener([Events::postLoad], $listener);
   ```

**Tip:** It's highly recommended to use Doctrine [entity listener](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/events.html#entity-listeners) when configuring the listener, so it is only executed for the entities it actually applies to.

### Symfony

In case you are using Symfony with Doctrine, you can register the listener as a service.

```yaml
services:
    tarifhaus.doctrine.nullable_embeddable.property_accessor:
        public: false
        class: Tarifhaus\Doctrine\ORM\NullableEmbeddable\PropertyAccessor
        arguments:
            - '@property_accessor'

    tarifhaus.doctrine.nullable_embeddable_closure_nullator:
        public: false
        class: Tarifhaus\Doctrine\ORM\NullableEmbeddable\ClosureNullator

    tarifhaus.doctrine.nullable_embeddable_listener:
        public: false
        class: Tarifhaus\Doctrine\ORM\NullableEmbeddableListener
        arguments:
            - '@tarifhaus.doctrine.nullable_embeddable.property_accessor'
            - '@tarifhaus.doctrine.nullable_embeddable_closure_nullator'
        calls:
            - ['addMapping', ['App\Domain\User\Model\UserProfile', 'address']]
        tags:
            - { name: 'doctrine.orm.entity_listener', entity: '\App\Domain\User\Model\UserProfile', event: 'postLoad' }
```
