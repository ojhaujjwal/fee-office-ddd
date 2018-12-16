<?php
declare(strict_types=1);


namespace App\FeeOffice\Domain\Model\Building;

use App\Core\Domain\ValueObjectInterface;
use Assert\Assertion;

final class BuildingName implements ValueObjectInterface
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

    public function sameValueAs(ValueObjectInterface $object): bool
    {
        return $object instanceof self && $this->value === $object->toString();
    }

}
