<?php
declare(strict_types=1);

namespace App\FeeOffice\Domain\Model\Apartment;

use App\Core\Domain\EntityInterface;
use App\FeeOffice\Domain\Event\ApartmentAddedToBuilding;
use App\FeeOffice\Domain\Event\ApartmentVacated;
use App\FeeOffice\Domain\Exception\UnnecessaryApartmentAttributeException;
use App\FeeOffice\Domain\Model\Building\BuildingId;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

final class Apartment extends AggregateRoot implements EntityInterface
{
    /**
     * @var ApartmentId
     */
    private $id;

    /**
     * @var ApartmentNumber
     */
    private $number;

    /**
     * @var BuildingId
     */
    private $buildingId;

    /**
     * @var int
     */
    private $entranceNumber;

    /**
     * @var AttributeValuesMap
     */
    private $attributeValues;

    /**
     * @var ApartmentOccupancyStatus
     */
    private $occupancyStatus;

    /**
     * @param $other
     *
     * @return bool
     */
    public function sameIdentityAs($other): bool
    {
        return $other instanceof self && $this->id->sameValueAs($other->id);
    }

    public static function addToBuilding(
        ApartmentId $id,
        ApartmentNumber $number,
        BuildingId $buildingId,
        int $entranceId,
        AttributeValuesMap $attributeValues,
        AttributesCollection $allAttributes
    ): self {
        foreach ($attributeValues as $attributeId => $value) {
            $id = AttributeId::fromString($attributeId);
            $attribute = $allAttributes->find($id);
            $attribute->assertValueValidity($value);
        }
        $allAttributes->assertMandatoryAttributesPresence(
            $attributeValues,
            ApartmentOccupancyStatus::get(ApartmentOccupancyStatus::VACANT)
        );

        $allAttributes->assertOccupiedApartmentAttributesAbsence($attributeValues);

        $apartment = new self();
        $apartment->recordThat(ApartmentAddedToBuilding::newInstance(
            $id,
            $number,
            $buildingId,
            $entranceId,
            $attributeValues
        ));

        return $apartment;
    }

    /**
     * @return ApartmentId
     */
    public function id(): ApartmentId
    {
        return $this->id;
    }

    /**
     * @return ApartmentNumber
     */
    public function number(): ApartmentNumber
    {
        return $this->number;
    }

    /**
     * @return BuildingId
     */
    public function buildingId(): BuildingId
    {
        return $this->buildingId;
    }

    /**
     * @return int
     */
    public function entranceNumber(): int
    {
        return $this->entranceNumber;
    }

    /**
     * @return AttributeValuesMap
     */
    public function attributeValues(): AttributeValuesMap
    {
        return $this->attributeValues;
    }

    public function isOccupied(): bool
    {
        return $this->occupancyStatus->is(ApartmentOccupancyStatus::OCCUPIED);
    }

    public function markAsOccupied(AttributeValuesMap $attributeValues, AttributesCollection $allAttributes): void
    {

    }

    public function markAsVacant(AttributesCollection $allAttributes): void
    {
        $this->recordThat(ApartmentVacated::newInstance($this->id, $allAttributes));
    }

    protected function apply(AggregateChanged $event): void
    {
        if ($event instanceof ApartmentAddedToBuilding) {
            $this->onApartmentAdded($event);
        } elseif ($event instanceof ApartmentVacated) {
            $this->onApartmentVacated($event);
        }
    }

    private function onApartmentAdded(ApartmentAddedToBuilding $event)
    {
        $this->id = $event->id();
        $this->number = $event->number();
        $this->buildingId = $event->buildingId();
        $this->entranceNumber = $event->entranceNumber();
        $this->attributeValues = $event->attributeValues();
        $this->occupancyStatus = ApartmentOccupancyStatus::get(ApartmentOccupancyStatus::VACANT);
    }

    private function onApartmentVacated(ApartmentVacated $event)
    {
        $this->attributeValues = $this->attributeValues->without(...$event->removedAttributeIds());
    }

    protected function aggregateId(): string
    {
        return $this->id->toString();
    }
}
