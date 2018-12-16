<?php

declare(strict_types=1);

namespace App\Core\Ports\Middleware;

use App\Core\Ports\Routing\RouteInfo;
use FastRoute;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RoutingMiddleware implements MiddlewareInterface
{
    /**
     * @var FastRoute\Dispatcher
     */
    private $dispatcher;

    public function __construct(FastRoute\Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $matchingRoute = $this->dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

        switch ($matchingRoute[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                $routeInfo = RouteInfo::fromNotFound();

                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $routeInfo = RouteInfo::fromMethodNotAllowed();

                break;
            default:
                // FastRoute\Dispatcher::FOUND
                $routeInfo = RouteInfo::fromSuccess(
                    is_array($matchingRoute[1]) ? $matchingRoute[1] : [$matchingRoute[1]],
                    $matchingRoute[2]
                );

                break;
        }

        $request = $request->withAttribute(RouteInfo::class, $routeInfo);

        if ($routeInfo->isSuccess()) {
            foreach ($routeInfo->getParams() as $key => $val) {
                $request = $request->withAttribute($key, $val);
            }
        }

        return $handler->handle($request);
    }
}
