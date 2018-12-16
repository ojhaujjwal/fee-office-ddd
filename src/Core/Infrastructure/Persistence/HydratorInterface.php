<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Persistence;

use Zend\Hydrator\ExtractionInterface;

interface HydratorInterface extends ExtractionInterface
{
    /**
     * @param array $data
     *
     * @return object
     */
    public function hydrate(array $data);
}
