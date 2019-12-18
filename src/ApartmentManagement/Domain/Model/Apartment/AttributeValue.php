<?php


namespace App\ApartmentManagement\Domain\Model\Apartment;


use App\Core\Domain\ValueObjectInterface;

final class AttributeValue implements ValueObjectInterface
{
    /**
     * @var AttributeId
     */
    private $attributeId;

    /**
     * @var mixed
     */
    private $value;

    /**
     * AttributeValue constructor.
     * @param AttributeId $attributeId
     * @param mixed $value
     */
    public function __construct(AttributeId $attributeId, $value)
    {
        $this->attributeId = $attributeId;
        $this->value = $value;
    }

    public static function forAttribute(Attribute $attribute, $value)
    {
        return new self($attribute->id(), $value);
    }

    /**
     * @return AttributeId
     */
    public function attributeId(): AttributeId
    {
        return $this->attributeId;
    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    public function sameValueAs($object): bool
    {
        return $object instanceof self && $object->attributeId === $this->attributeId && $object->value === $this->value;
    }
}
