<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Persistence;

use Zend\Db\ResultSet\AbstractResultSet;

class HydratingResultSet extends AbstractResultSet
{
    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * HydratingResultSet constructor.
     *
     * @param HydratorInterface $hydrator
     */
    public function __construct(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    /**
     * Iterator: get current item.
     *
     * @return null|object
     */
    public function current(): ?object
    {
        if (null === $this->buffer) {
            $this->buffer = -2; // implicitly disable buffering from here on
        } elseif (is_array($this->buffer) && isset($this->buffer[$this->position])) {
            return $this->buffer[$this->position];
        }
        $data = $this->dataSource->current();
        $object = is_array($data) ? $this->hydrator->hydrate($data) : null;

        if (is_array($this->buffer)) {
            $this->buffer[$this->position] = $object;
        }

        return $object;
    }

    /**
     * Cast result set to array of arrays.
     *
     * @return array
     */
    public function toArray(): array
    {
        $return = [];
        foreach ($this as $row) {
            $return[] = $this->hydrator->extract($row);
        }

        return $return;
    }
}
