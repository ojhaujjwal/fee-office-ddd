<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Factory;

use Psr\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Sql;

final class SqlFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new Sql(
            $container->get(AdapterInterface::class)
        );
    }
}
