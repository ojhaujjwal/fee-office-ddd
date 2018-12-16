<?php

declare(strict_types=1);

namespace App\Core\Domain;

interface ValueObjectInterface
{
    /**
     * @param ValueObjectInterface $object
     * @return bool
     */
    public function sameValueAs($object): bool;
}
