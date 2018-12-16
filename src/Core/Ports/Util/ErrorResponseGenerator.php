<?php

declare(strict_types=1);

namespace App\Core\Ports\Util;

use App\Core\Ports\Exception\Api\ApiExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Throwable;
use Zend\ProblemDetails\ProblemDetailsResponseFactory;

class ErrorResponseGenerator
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * ErrorResponseGenerator constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Generates HTTP response for cases when exceptions are raised.
     *
     * @param Throwable              $e
     * @param ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Throwable $e, ServerRequestInterface $request)
    {
        if (!$e instanceof ApiExceptionInterface || 500 === $e->getStatus()) {
            /** @var LoggerInterface $logger */
            $logger = $this->container->get(LoggerInterface::class);
            $logger->error($e);
        }

        /** @var ProblemDetailsResponseFactory $problemDetailsResponseFactory */
        $problemDetailsResponseFactory = $this->container->get(ProblemDetailsResponseFactory::class);

        return $problemDetailsResponseFactory->createResponseFromThrowable($request, $e);
    }
}
