<?php
declare(strict_types=1);


namespace App\ApartmentManagement\Domain\Model\Building;

use App\Core\Domain\EntityInterface;
use App\ApartmentManagement\Domain\Event\BuildingAdded;
use App\ApartmentManagement\Domain\Model\Entrance\Entrance;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

final class Building extends AggregateRoot implements EntityInterface
{
    /**
     * @var BuildingId
     */
    private $id;

    /**
     * @var BuildingName
     */
    private $name;

    /**
     * @var Entrance[]
     */
    private $entrances;

    /**
     * @param $other
     *
     * @return bool
     */
    public function sameIdentityAs($other): bool
    {
        return $other instanceof self && $this->id->sameValueAs($other->id);
    }

    /**
     * @param BuildingId $id
     * @param BuildingName $name
     * @param array $entrances
     * @return Building
     */
    public static function add(BuildingId $id, BuildingName $name, array $entrances): self
    {
        $building = new self();
        $building->recordThat(BuildingAdded::newInstance($id->toString(), $name->toString(), $entrances));

        return $building;
    }

    protected function aggregateId(): string
    {
        return $this->id->toString();
    }

    /**
     * @param AggregateChanged $event
     * @throws \Exception
     */
    protected function apply(AggregateChanged $event): void
    {
        if ($event instanceof BuildingAdded) {
            $this->whenBuildingAdded($event);
        }
    }

    /**
     * @param BuildingAdded $event
     * @throws \Exception
     */
    protected function whenBuildingAdded(BuildingAdded $event): void
    {
        $this->id = $event->id();
        $this->name = $event->name();
        $this->entrances = $event->entrances();
    }
}
