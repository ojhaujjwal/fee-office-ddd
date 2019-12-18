<?php
declare(strict_types=1);

namespace App\ContactAdministration\Domain\Model;

use App\Core\Domain\ValueObjectInterface;

final class FirstName implements ValueObjectInterface
{
    /**
     * @var string
     */
    private $value;

    /**
     * BuildingName constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        try {
            \AssertionExample::notEmpty($value);
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
