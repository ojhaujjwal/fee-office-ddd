<?php
declare(strict_types=1);

namespace App\ApartmentManagement\Domain\Event;

use App\ApartmentManagement\Domain\Model\Building\BuildingId;
use App\ApartmentManagement\Domain\Model\Building\BuildingName;
use App\ApartmentManagement\Domain\Model\Entrance\Address;
use App\ApartmentManagement\Domain\Model\Entrance\Entrance;
use Assert\Assertion;
use Prooph\EventSourcing\AggregateChanged;

final class BuildingAdded extends AggregateChanged
{
    public static function newInstance(string $id, string $name, array $entrances): self
    {
        $entranceId = 0;

        return self::occur($id, [
            'name' => $name,
            'entrances' => array_map(function(&$entrance) use (&$entranceId) {
                $entranceId = $entranceId + 1;
                $entrance['id'] = $entranceId;
                return $entrance;
            }, $entrances)
        ]);
    }

    public function id(): BuildingId
    {
        return BuildingId::fromString($this->aggregateId());
    }

    public function name(): BuildingName
    {
        return new BuildingName($this->payload()['name']);
    }

    /**
     * @return Entrance[]
     * @throws \Exception
     */
    public function entrances()
    {
        $entrances = $this->payload()['entrances'];

        if (!count($entrances)) {
            throw new \Exception('Building must have at-least one entrance');
        }

        return array_map(function ($entrance) use (&$index) {
            Assertion::keyExists($entrance, 'address');
            Assertion::keyExists($entrance, 'apartments');

            return new Entrance($entrance['id'], new Address($entrance['address']));
        }, $entrances);
    }
}
