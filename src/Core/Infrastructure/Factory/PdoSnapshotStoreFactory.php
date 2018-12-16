<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Factory;

use Prooph\SnapshotStore\Pdo\PdoSnapshotStore;
use Psr\Container\ContainerInterface;

final class PdoSnapshotStoreFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var \PDO $pdo */
        $pdo = $container->get(\PDO::class);

        return new PdoSnapshotStore($pdo);
    }
}
