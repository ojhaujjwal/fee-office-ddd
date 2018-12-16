<?php

declare(strict_types=1);

namespace App\Core\Domain;

interface ValueObjectInterface
{
    public function sameValueAs($object): bool;
}
