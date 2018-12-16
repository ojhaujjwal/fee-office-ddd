<?php

declare(strict_types=1);

namespace App\Core\Domain;

interface EntityInterface
{
    public function sameIdentityAs($other): bool;
}
