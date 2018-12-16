<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Factory;

use League\Tactician\CommandBus;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Logger\Formatter\ClassPropertiesFormatter;
use League\Tactician\Logger\LoggerMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

final class CommandBusFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');

        $logger = $container->get(LoggerInterface::class);

        return new CommandBus([
            new LoggerMiddleware(new ClassPropertiesFormatter(), $logger),
            new CommandHandlerMiddleware(
                new ClassNameExtractor(),
                new ContainerLocator(
                    $container,
                    $config['command_handlers']
                ),
                new HandleInflector()
            ),
        ]);
    }
}
