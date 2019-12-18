<?php

declare(strict_types=1);

use Zend\ConfigAggregator\ConfigAggregator;

return (new ConfigAggregator([
    new \Zend\ConfigAggregator\ArrayProvider([
        'services' => require(__DIR__.'/services.php'),
        'db' => [
            'driver' => 'Pdo_Pgsql',
            'database' => 'fee_office',
            'hostname' => getenv('PHINX_DBHOST') ?: 'postgres',
            'username' => getenv('PHINX_DBUSER') ?: 'fee_office',
            'password' => getenv('PHINX_DBPASS') ?: 'fee_office',
            'port' => getenv('PHINX_DBPORT') ?: 5432,
        ],
        'environment' => getenv('HF_ENVIRONMENT') ?: 'development',
        'debug' => 'development' === (getenv('HF_ENVIRONMENT') ?: 'development'),

        'elastic' => [
            'hosts' => explode(',', getenv('ELASTIC_HOST')),
        ],
    ]),
    App\Core\ConfigProvider::class,
    App\ApartmentManagement\ConfigProvider::class,
    App\ContactAdministration\ConfigProvider::class,
]))->getMergedConfig();
