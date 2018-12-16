<?php

declare(strict_types=1);

namespace App\Core\Ports\Middleware;

use App\Core\Ports\Routing\RouteInfo;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Stratigility\MiddlewarePipe;

final class DispatcherMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var RouteInfo $routeInfo */
        $routeInfo = $request->getAttribute(RouteInfo::class, false);
        if (!$routeInfo || !$routeInfo->isSuccess()) {
            return $handler->handle($request);
        }

        if (1 === count($routeInfo->getHandlers())) {
            /** @var MiddlewareInterface $dispatcher */
            $dispatcher = $this->container->get($routeInfo->getHandlers()[0]);
        } else {
            $dispatcher = new MiddlewarePipe();
            foreach ($routeInfo->getHandlers() as $routeHandler) {
                $dispatcher->pipe($this->container->get($routeHandler));
            }
        }

        return $dispatcher->process($request, $handler);
    }
}
