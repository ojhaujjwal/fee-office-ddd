<?php


namespace App\ApartmentManagement\Domain\Model\Apartment;


use App\Core\Domain\ValueObjectInterface;

final class ApartmentNumber implements ValueObjectInterface
{
    private $number;

    public static function fromString(string $number): self
    {
        return new self($number);
    }

    private function __construct(string $number)
    {
        $this->number = $number;
    }

    public function toString(): string
    {
        return $this->number;
    }

    public function sameValueAs($other): bool
    {
        return $other instanceof self and $this->number === $other->number;
    }

    public function __toString(): string
    {
        return $this->number;
    }
}
