<?php

declare(strict_types=1);

use Zend\ServiceManager\ServiceManager;

// Load configuration
$config = require __DIR__.'/config.php';

// Build container
$container = new ServiceManager($config['services']);
$container->setService('config', $config);
$container->setService(\Psr\Container\ContainerInterface::class, $container);

return $container;
