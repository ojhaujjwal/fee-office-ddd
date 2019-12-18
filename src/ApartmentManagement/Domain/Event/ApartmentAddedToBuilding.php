<?php
declare(strict_types=1);

namespace App\ApartmentManagement\Domain\Event;

use App\ApartmentManagement\Domain\Model\Apartment\ApartmentId;
use App\ApartmentManagement\Domain\Model\Apartment\ApartmentNumber;
use App\ApartmentManagement\Domain\Model\Apartment\AttributeId;
use App\ApartmentManagement\Domain\Model\Apartment\AttributeValue;
use App\ApartmentManagement\Domain\Model\Apartment\AttributeValuesMap;
use App\ApartmentManagement\Domain\Model\Building\BuildingId;
use Prooph\EventSourcing\AggregateChanged;

final class ApartmentAddedToBuilding extends AggregateChanged
{
    public static function newInstance(
        ApartmentId $id,
        ApartmentNumber $number,
        BuildingId $buildingId,
        int $entranceNumber,
        AttributeValuesMap $attributeValues
    ) {
        return self::occur($id->toString(), [
            'number' => $number->toString(),
            'building_id' => $buildingId->toString(),
            'entrance_number' => $entranceNumber,
            'attribute_values' => $attributeValues->toArray()
        ]);
    }

    public function id(): ApartmentId
    {
        return ApartmentId::fromString($this->aggregateId());
    }

    public function number(): ApartmentNumber
    {
        return ApartmentNumber::fromString($this->payload()['number']);
    }

    public function buildingId(): BuildingId
    {
        return BuildingId::fromString($this->payload()['building_id']);
    }

    public function entranceNumber(): int
    {
        return $this->payload()['entrance_number'];
    }

    public function attributeValues(): AttributeValuesMap
    {
        $attributeValues = [];
        foreach ($this->payload['attribute_values'] as $attributeId => $value) {
            $attributeValues[] = new AttributeValue(AttributeId::fromString($attributeId), $value);
        }

        return new AttributeValuesMap(...$attributeValues);
    }
}
