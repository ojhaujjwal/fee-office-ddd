<?php

declare(strict_types=1);

namespace App\Core\Ports\Middleware;

use App\Core\Ports\Middleware\DispatcherMiddleware;
use Psr\Container\ContainerInterface;

final class DispatcherMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): DispatcherMiddleware
    {
        return new DispatcherMiddleware($container);
    }
}
