<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Factory;

use Prooph\EventStore\EventStore;
use Prooph\EventStore\Pdo\Projection\PostgresProjectionManager;
use Psr\Container\ContainerInterface;

final class PdoProjectionManagerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var \PDO $pdo */
        $pdo = $container->get(\PDO::class);
        $eventStore = $container->get(EventStore::class);

        return new PostgresProjectionManager($eventStore, $pdo);
    }
}
