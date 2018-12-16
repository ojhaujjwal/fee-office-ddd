<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Infrastructure\Factory\EventBusFactory;
use App\Core\Infrastructure\Factory\PdoEventStoreFactory;
use App\Core\Infrastructure\Factory\PdoFactory;
use App\Core\Infrastructure\Factory\PdoProjectionManagerFactory;
use App\Core\Infrastructure\Factory\PdoSnapshotStoreFactory;
use Prooph\Common\Event\ActionEventEmitter;
use Prooph\Common\Event\ProophActionEventEmitter;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Projection\ProjectionManager;
use Prooph\EventStore\TransactionalEventStore;
use Prooph\ServiceBus\EventBus;
use Prooph\SnapshotStore\SnapshotStore;
use Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'command_handlers' => $this->getCommandHandlers(),
            'services' => $this->getServices(),
        ];
    }

    public function getCommandHandlers(): array
    {
        return [
            Ports\Command\ValidateRequest::class => Ports\Handler\ValidateRequestHandler::class,
            Ports\Command\FractalScopeToJsonResponse::class => Ports\Handler\FractalScopeToJsonResponseHandler::class,
        ];
    }

    public function getServices(): array
    {
        return [
            'aliases' => [
                EventStore::class => TransactionalEventStore::class,
            ],
            'factories' => [
                \Zend\Stratigility\MiddlewarePipe::class => Ports\Factory\MiddlewarePipeFactory::class,
                Ports\Middleware\DispatcherMiddleware::class => Ports\Middleware\DispatcherMiddlewareFactory::class,
                \Zend\Stratigility\Middleware\ErrorHandler::class => Infrastructure\Factory\ErrorHandlerFactory::class,
                \FastRoute\Dispatcher::class => Infrastructure\Factory\FastRouteDispatcherFactory::class,
                Ports\Middleware\RoutingMiddleware::class => Ports\Middleware\RoutingMiddlewareFactory::class,
                Ports\Middleware\BodyParserMiddleware::class => ReflectionBasedAbstractFactory::class,
                \Zend\ProblemDetails\ProblemDetailsResponseFactory::class => Infrastructure\Factory\ProblemDetailsResponseFactoryFactory::class,
                \Zend\Db\Sql\Sql::class => Infrastructure\Factory\SqlFactory::class,
                \League\Tactician\CommandBus::class => Infrastructure\Factory\CommandBusFactory::class,
                \Psr\Log\LoggerInterface::class => Infrastructure\Factory\LoggerFactory::class,
                Ports\Factory\FractalManagerFactory::class => ReflectionBasedAbstractFactory::class,
                \Elasticsearch\Client::class => Infrastructure\Factory\ElasticClientFactory::class,
                TransactionalEventStore::class => PdoEventStoreFactory::class,
                EventBus::class => EventBusFactory::class,
                SnapshotStore::class => PdoSnapshotStoreFactory::class,
                \PDO::class => PdoFactory::class,
                ProjectionManager::class => PdoProjectionManagerFactory::class,
            ],
            'invokables' => [
                Ports\Middleware\NotFoundMiddleware::class => Ports\Middleware\NotFoundMiddleware::class,
                Ports\Handler\FractalScopeToJsonResponseHandler::class => Ports\Handler\FractalScopeToJsonResponseHandler::class,
                Ports\Handler\ValidateRequestHandler::class => Ports\Handler\ValidateRequestHandler::class,
                Ports\Middleware\SecureResponseHeadersMiddleware::class => Ports\Middleware\SecureResponseHeadersMiddleware::class,
                ActionEventEmitter::class => ProophActionEventEmitter::class,
            ],
            'shared' => [
                \Zend\Db\Sql\Sql::class => false,
            ],
        ];
    }
}
