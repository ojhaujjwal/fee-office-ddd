<?php


namespace App\FeeOffice\Domain\Model\Apartment;


use App\FeeOffice\Domain\Exception\ApartmentAttributeRequiredException;
use App\FeeOffice\Domain\Exception\UnnecessaryApartmentAttributeException;
use Assert\Assertion;
use Assert\AssertionFailedException;

final class AttributesCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var Attribute[]
     */
    private $attributes;

    public function __construct(Attribute... $attributes)
    {
        $this->attributes = $attributes;
    }


    public function getIterator()
    {
        return new \ArrayIterator($this->attributes);
    }

    public function filterByRequirementSpec(AttributeRequirementSpec... $specifications)
    {
        return new self(...array_filter($this->attributes, function(Attribute $attribute) use ($specifications) {
            foreach ($specifications as $specification) {
                if ($attribute->requirementSpec()->is($specification)) {
                    return true;
                }
            }

            return false;
        }));
    }

    public function filterMandatory(): self
    {
        return $this->filterByRequirementSpec(AttributeRequirementSpec::get(AttributeRequirementSpec::MANDATORY));
    }

    /**
     * @param AttributeId $attributeId
     * @return bool
     */
    public function contains(AttributeId $attributeId): bool
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->id()->sameValueAs($attributeId)) {
                return true;
            }
        }

        return false;
    }

    public function find(AttributeId $attributeId): ?Attribute
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->id()->sameValueAs($attributeId)) {
                return $attribute;
            }
        }

        return null;
    }

    public function count(): int
    {
        return count($this->attributes);
    }

    /**
     * @param AttributeValuesMap $attributeValues
     * @param ApartmentOccupancyStatus $occupancyStatus
     */
    public function assertMandatoryAttributesPresence(AttributeValuesMap $attributeValues, ApartmentOccupancyStatus $occupancyStatus): void
    {
        if ($occupancyStatus->is(ApartmentOccupancyStatus::OCCUPIED)) {
            $requiredAttributes = $this->filterByRequirementSpec(
                AttributeRequirementSpec::get(AttributeRequirementSpec::MANDATORY),
                AttributeRequirementSpec::get(AttributeRequirementSpec::MANDATORY_FOR_OCCUPIED_APARTMENT)
            );
        } else {
            $requiredAttributes = $this->filterMandatory();
        }

        if (count($requiredAttributes) > count($attributeValues)) {
            throw new ApartmentAttributeRequiredException();
        }

        foreach ($requiredAttributes->attributes as $attribute) {
            if (!$attributeValues->has($attribute->id())) {
                throw new ApartmentAttributeRequiredException();
            }

            $value = $attributeValues->get($attribute->id());

            if ($attribute->type()->isAlphanumeric()) {
                try{
                    Assertion::notEmpty($value);
                } catch (AssertionFailedException $exception) {
                    throw new ApartmentAttributeRequiredException();
                }
            }
        }
    }

    public function assertOccupiedApartmentAttributesAbsence(AttributeValuesMap $attributeValues)
    {
        $occupiedOnlyAttributes = $this->filterByRequirementSpec(
            AttributeRequirementSpec::get(AttributeRequirementSpec::MANDATORY_FOR_OCCUPIED_APARTMENT)
        );

        if (count($attributeValues) > (count($this) - count($occupiedOnlyAttributes))) {
            throw new UnnecessaryApartmentAttributeException();
        }

        foreach ($occupiedOnlyAttributes as $attribute) {
            if ($attributeValues->has($attribute->id())) {
                throw new UnnecessaryApartmentAttributeException();
            }
        }
    }
}
