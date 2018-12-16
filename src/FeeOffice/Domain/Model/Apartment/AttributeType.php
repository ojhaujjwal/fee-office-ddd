<?php


namespace App\FeeOffice\Domain\Model\Apartment;


use App\Core\Domain\Enum;

final class AttributeType extends Enum
{
    public const NUMERIC = 'numeric';
    public const ALPHANUMERIC = 'alphanumeric';

    public function isNumeric(): bool
    {
        return $this->is(self::NUMERIC);
    }

    public function isAlphanumeric(): bool
    {
        return $this->is(self::ALPHANUMERIC);
    }
}
