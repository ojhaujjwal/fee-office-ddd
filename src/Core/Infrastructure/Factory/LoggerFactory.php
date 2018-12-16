<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Factory;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

final class LoggerFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @throws \Exception
     *
     * @return Logger
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var array $config */
        $config = $container->get('config');

        $logger = new Logger($config['environment']);
        $logger->pushHandler(new StreamHandler('logs/all.log'));

        return $logger;
    }
}
