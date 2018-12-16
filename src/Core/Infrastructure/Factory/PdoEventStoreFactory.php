<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Factory;

use Prooph\Common\Event\ActionEventEmitter;
use Prooph\Common\Messaging\FQCNMessageFactory;
use Prooph\EventStore\Pdo\PersistenceStrategy\PostgresSingleStreamStrategy;
use Prooph\EventStore\Pdo\PostgresEventStore;
use Prooph\EventStore\TransactionalActionEventEmitterEventStore;
use Psr\Container\ContainerInterface;

final class PdoEventStoreFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var \PDO $pdo */
        $pdo = $container->get(\PDO::class);

        $eventEmitter = $container->get(ActionEventEmitter::class);

        return new TransactionalActionEventEmitterEventStore(
            new PostgresEventStore(new FQCNMessageFactory(), $pdo, new PostgresSingleStreamStrategy()),
            $eventEmitter
        );
    }
}
