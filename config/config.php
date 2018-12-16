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

        // Hard-coding basic auth credentials
        // this is just a simple authentication demonstration for the test
        // not recommended for a production application
        'authentication_credentials' => [
            'username1' => 'password1',
            'username2' => 'password2',
        ],

        'elastic' => [
            'hosts' => explode(',', getenv('ELASTIC_HOST')),
        ],
    ]),
    App\Core\ConfigProvider::class,
    App\FeeOffice\ConfigProvider::class,
]))->getMergedConfig();
