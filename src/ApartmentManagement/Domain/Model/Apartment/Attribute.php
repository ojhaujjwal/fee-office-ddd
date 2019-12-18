<?php


namespace App\ApartmentManagement\Domain\Model\Apartment;


use Assert\Assertion;

final class Attribute
{
    public const AREA_ID = 'f1c7fabc-4685-4329-99d3-64af9a6a7cc9';
    public const BODY_COUNT_ID = '7489e2b5-42ee-4ae6-b023-8cf791188b13';

    /**
     * @var AttributeId
     */
    private $id;

    /**
     * @var AttributeLabel
     */
    private $label;

    /**
     * @var AttributeType
     */
    private $type;

    /**
     * @var AttributeRequirementSpec
     */
    private $requirementSpec;

    /**
     * Attribute constructor.
     * @param AttributeId $id
     * @param AttributeLabel $label
     * @param AttributeType $type
     * @param AttributeRequirementSpec $requirementSpec
     */
    public function __construct(AttributeId $id, AttributeLabel $label, AttributeType $type, AttributeRequirementSpec $requirementSpec)
    {
        $this->id = $id;
        $this->label = $label;
        $this->type = $type;
        $this->requirementSpec = $requirementSpec;
    }

    public static function area(): self
    {
        return new self(
            AttributeId::fromString(self::AREA_ID),
            new AttributeLabel('Area'),
            AttributeType::get(AttributeType::NUMERIC),
            AttributeRequirementSpec::get(AttributeRequirementSpec::MANDATORY)
        );
    }

    public static function bodyCount(): self
    {
        return new self(
            AttributeId::fromString(self::BODY_COUNT_ID),
            new AttributeLabel('Body Count'),
            AttributeType::get(AttributeType::NUMERIC),
            AttributeRequirementSpec::get(AttributeRequirementSpec::MANDATORY_FOR_OCCUPIED_APARTMENT)
        );
    }

    /**
     * @return AttributeId
     */
    public function id(): AttributeId
    {
        return $this->id;
    }

    /**
     * @return AttributeLabel
     */
    public function label(): AttributeLabel
    {
        return $this->label;
    }

    /**
     * @return AttributeType
     */
    public function type(): AttributeType
    {
        return $this->type;
    }

    public function requirementSpec(): AttributeRequirementSpec
    {
        return $this->requirementSpec;
    }

    /**
     * @return bool
     */
    public function isOptional(): bool
    {
        return $this->requirementSpec->is(AttributeRequirementSpec::OPTIONAL);
    }

    /**
     * @return bool
     */
    public function isMandatory(): bool
    {
        return $this->requirementSpec->is(AttributeRequirementSpec::MANDATORY);
    }

    /**
     * @return bool
     */
    public function isMandatoryForOccupiedApartment(): bool
    {
        return $this->requirementSpec->is(AttributeRequirementSpec::MANDATORY_FOR_OCCUPIED_APARTMENT);
    }

    public function assertValueValidity($value): void
    {
        if ($this->type()->isNumeric()) {
            Assertion::numeric($value);
        } else {
            Assertion::string($value);
        }
    }
}
