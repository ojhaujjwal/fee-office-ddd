<?php
declare(strict_types=1);

namespace Specification\Core;

use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventSourcing\AggregateRoot;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;

abstract class AbstractAggregateSpec
{
    /**
     * @var AggregateTranslator
     */
    private $aggregateTranslator;

    protected function popRecordedEvents(AggregateRoot $aggregateRoot): array
    {
        return $this->getAggregateTranslator()->extractPendingStreamEvents($aggregateRoot);
    }

    protected function reconstituteAggregateFromHistory(string $aggregateRootClass, array $events)
    {
        return $this->getAggregateTranslator()->reconstituteAggregateFromHistory(
            AggregateType::fromAggregateRootClass($aggregateRootClass),
            new \ArrayIterator($events)
        );
    }

    private function getAggregateTranslator(): AggregateTranslator
    {
        if (null === $this->aggregateTranslator) {
            $this->aggregateTranslator = new AggregateTranslator();
        }
        return $this->aggregateTranslator;
    }
}
