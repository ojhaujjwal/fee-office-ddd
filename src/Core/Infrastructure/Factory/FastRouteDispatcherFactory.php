<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Factory;

use FastRoute;
use Psr\Container\ContainerInterface;

final class FastRouteDispatcherFactory
{
    public function __invoke(ContainerInterface $container): FastRoute\Dispatcher
    {
        return FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $routeCollector) {
            (require 'config/routes.php')($routeCollector);
        });
    }
}
