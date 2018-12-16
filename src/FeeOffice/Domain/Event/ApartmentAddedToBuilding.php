<?php
declare(strict_types=1);

namespace App\FeeOffice\Domain\Event;

use App\FeeOffice\Domain\Model\Apartment\ApartmentId;
use App\FeeOffice\Domain\Model\Apartment\ApartmentNumber;
use App\FeeOffice\Domain\Model\Apartment\AttributeId;
use App\FeeOffice\Domain\Model\Apartment\AttributeValue;
use App\FeeOffice\Domain\Model\Apartment\AttributeValuesMap;
use App\FeeOffice\Domain\Model\Building\BuildingId;
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
            'buildingId' => $buildingId->toString(),
            'entranceNumber' => $entranceNumber,
            'attributeValues' => $attributeValues->toArray()
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
        return BuildingId::fromString($this->payload()['buildingId']);
    }

    public function entranceNumber(): int
    {
        return $this->payload()['entranceNumber'];
    }

    public function attributeValues(): AttributeValuesMap
    {
        $attributeValues = [];
        foreach ($this->payload['attributeValues'] as $attributeId => $value) {
            $attributeValues = new AttributeValue(AttributeId::fromString($attributeId), $value);
        }

        return new AttributeValuesMap(...$attributeValues);
    }
}
