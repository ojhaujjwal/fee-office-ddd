<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Projection;

use Elasticsearch\Client;
use Prooph\EventStore\Projection\AbstractReadModel;

abstract class AbstractElasticSearchModel extends AbstractReadModel
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * AbstractElasticSearchModel constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function init(): void
    {
    }

    public function isInitialized(): bool
    {
        return true;
    }

    public function reset(): void
    {
        $this->client->deleteByQuery([
            'index' => $this->getIndex(),
            'type' => $this->getIndex(),
            'body' => [
                'query' => [
                    'match_all' => new \ArrayObject(),
                ],
            ],
        ]);
    }

    public function delete(): void
    {
        $this->reset();
    }

    abstract protected function getIndex(): string;
}
