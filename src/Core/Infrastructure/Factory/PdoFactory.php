<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Factory;

use Psr\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;

final class PdoFactory
{
    public function __invoke(ContainerInterface $container): \PDO
    {
        /** @var AdapterInterface $dbAdapter */
        $dbAdapter = $container->get(AdapterInterface::class);

        return $dbAdapter->getDriver()->getConnection()->getResource();
    }
}
