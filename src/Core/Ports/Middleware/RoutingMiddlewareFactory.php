<?php

declare(strict_types=1);

namespace App\Core\Ports\Middleware;

use App\Core\Ports\Middleware\RoutingMiddleware;
use FastRoute\Dispatcher;
use Psr\Container\ContainerInterface;

final class RoutingMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): RoutingMiddleware
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = $container->get(Dispatcher::class);

        return new RoutingMiddleware($dispatcher);
    }
}
