<?php
declare(strict_types=1);


namespace App\ApartmentManagement\Domain\Model\Entrance;

use App\Core\Domain\ValueObjectInterface;
use Assert\Assertion;

final class Address implements ValueObjectInterface
{
    /**
     * @var string
     */
    private $value;

    /**
     * Address constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        try {
            Assertion::notEmpty($value);
        } catch (\Exception $e) {
            // TODO: throw exception
        }
        $this->value = $value;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function sameValueAs($object): bool
    {
        return $object instanceof self && $this->value === $object->toString();
    }
}
