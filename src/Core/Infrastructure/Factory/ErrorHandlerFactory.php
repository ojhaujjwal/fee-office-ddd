<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Factory;

use App\Core\Ports\Util\ErrorResponseGenerator;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response;
use Zend\Stratigility\Middleware\ErrorHandler;

final class ErrorHandlerFactory
{
    public function __invoke(ContainerInterface $container): ErrorHandler
    {
        return new ErrorHandler(
            function () {
                return new Response();
            },
            new ErrorResponseGenerator($container)
        );
    }
}
