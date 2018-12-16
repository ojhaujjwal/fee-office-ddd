<?php

declare(strict_types=1);

namespace App\Core\Domain;

use MabeEnum\Enum as MabeEnum;
use MabeEnum\EnumSerializableTrait;
use Serializable;

abstract class Enum extends MabeEnum implements Serializable, ValueObjectInterface
{
    use EnumSerializableTrait;

    public function sameValueAs($object): bool
    {
        return $this->is($object);
    }

    public function toString(): string
    {
        return $this->getName();
    }
}
