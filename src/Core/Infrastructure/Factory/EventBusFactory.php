<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Factory;

use Prooph\Common\Event\ActionEventEmitter;
use Prooph\EventStore\EventStore;
use Prooph\EventStoreBusBridge\EventPublisher;
use Prooph\ServiceBus\EventBus;
use Psr\Container\ContainerInterface;

final class EventBusFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $eventEmitter = $container->get(ActionEventEmitter::class);
        $eventStore = $container->get(EventStore::class);

        $eventBus = new EventBus($eventEmitter);
        $eventPublisher = new EventPublisher($eventBus);
        $eventPublisher->attachToEventStore($eventStore);

        return $eventBus;
    }
}
