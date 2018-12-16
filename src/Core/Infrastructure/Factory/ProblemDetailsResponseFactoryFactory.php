<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Factory;

use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response;
use Zend\ProblemDetails\ProblemDetailsResponseFactory;

final class ProblemDetailsResponseFactoryFactory
{
    public function __invoke(ContainerInterface $container): ProblemDetailsResponseFactory
    {
        $config = $container->get('config');

        return new ProblemDetailsResponseFactory(
            function () {
                return new Response();
            },
            $config['debug'],
            null,
            $config['debug']
        );
    }
}
