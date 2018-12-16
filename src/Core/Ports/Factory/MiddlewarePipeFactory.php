<?php

declare(strict_types=1);

namespace App\Core\Ports\Factory;

use App\Core\Ports\Middleware\BodyParserMiddleware;
use App\Core\Ports\Middleware\DispatcherMiddleware;
use App\Core\Ports\Middleware\NotFoundMiddleware;
use App\Core\Ports\Middleware\RoutingMiddleware;
use App\Core\Ports\Middleware\SecureResponseHeadersMiddleware;
use Psr\Container\ContainerInterface;
use Zend\Stratigility\Middleware\ErrorHandler;
use Zend\Stratigility\MiddlewarePipe;

final class MiddlewarePipeFactory
{
    public function __invoke(ContainerInterface $container): MiddlewarePipe
    {
        $pipeline = new MiddlewarePipe();

        $pipeline->pipe($container->get(ErrorHandler::class));
        $pipeline->pipe($container->get(RoutingMiddleware::class));
        $pipeline->pipe($container->get(BodyParserMiddleware::class));
        $pipeline->pipe($container->get(SecureResponseHeadersMiddleware::class));
        $pipeline->pipe($container->get(DispatcherMiddleware::class));
        $pipeline->pipe($container->get(NotFoundMiddleware::class));

        return $pipeline;
    }
}
