<?php

declare(strict_types=1);

use Psr\Log\LoggerInterface;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use Zend\HttpHandlerRunner\RequestHandlerRunner;
use Zend\Stratigility\MiddlewarePipe;

// Set internal character encoding to UTF-8
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');

chdir(dirname(__DIR__));

require __DIR__.'/../vendor/autoload.php';

/**
 * Self calling anonymous function which keeps the global scope clean.
 */
(function () {
    $container = require __DIR__.'/../config/container.php';

    $app = new RequestHandlerRunner(
        $container->get(MiddlewarePipe::class),
        new SapiEmitter(),
        function () {
            return ServerRequestFactory::fromGlobals();
        },
        function ($exception) use ($container) {
            // called when there is an error in generating the request object
            // should never reach this point
            // but we are handling it anyway

            /** @var LoggerInterface $logger */
            $logger = $container->get(LoggerInterface::class);
            $logger->error($exception);

            return new Zend\Diactoros\Response(new \Zend\Diactoros\Stream('php://tmp'), 500);
        }
    );

    $app->run();
})();
