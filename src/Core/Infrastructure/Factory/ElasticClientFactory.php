<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Factory;

use Elasticsearch\ClientBuilder;
use Psr\Container\ContainerInterface;

final class ElasticClientFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');

        return ClientBuilder::create()
            ->setHosts($config['elastic']['hosts'])
            ->build();
    }
}
