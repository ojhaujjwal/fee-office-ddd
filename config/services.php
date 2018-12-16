<?php

declare(strict_types=1);

return [
    'factories' => [
        \Zend\Db\Adapter\AdapterInterface::class => \Zend\Db\Adapter\AdapterServiceFactory::class,
    ],
    'invokables' => [
        \Negotiation\Negotiator::class => \Negotiation\Negotiator::class,
        \League\Event\EmitterInterface::class => \League\Event\Emitter::class,
    ],
];
