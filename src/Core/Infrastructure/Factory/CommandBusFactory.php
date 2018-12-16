<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Factory;

use App\Core\Domain\AbstractDomainCommand;
use League\Tactician\CommandBus;
use League\Tactician\CommandEvents\Event\CommandHandled;
use League\Tactician\CommandEvents\EventMiddleware;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Logger\Formatter\ClassPropertiesFormatter;
use League\Tactician\Logger\LoggerMiddleware;
use Prooph\EventStore\TransactionalEventStore;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

final class CommandBusFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');

        $logger = $container->get(LoggerInterface::class);

        $eventMiddleware = new EventMiddleware();

        /**
         * @return TransactionalEventStore
         */
        $eventStoreResolver = function()  use ($container)  {
            return $container->get(TransactionalEventStore::class);
        };

        $eventMiddleware->addListener('command.received', function (CommandHandled $event) use ($eventStoreResolver) {
            $command = $event->getCommand();
            if ($command instanceof AbstractDomainCommand) {
                $eventStoreResolver()->beginTransaction();
            }
        });

        $eventMiddleware->addListener('command.handled', function (CommandHandled $event) use ($eventStoreResolver) {
            $command = $event->getCommand();
            if ($command instanceof AbstractDomainCommand) {
                $eventStoreResolver()->commit();
            }
        });

        $eventMiddleware->addListener('command.failed', function (CommandHandled $event) use ($eventStoreResolver) {
            $command = $event->getCommand();
            if ($command instanceof AbstractDomainCommand) {
                $eventStoreResolver()->rollback();
            }
        });

        return new CommandBus([
            new LoggerMiddleware(new ClassPropertiesFormatter(), $logger),
            $eventMiddleware,
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
