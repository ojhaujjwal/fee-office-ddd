<?php
declare(strict_types=1);

namespace App\ApartmentManagement\Domain\Event;

use App\ApartmentManagement\Domain\Model\Apartment\ApartmentId;
use App\ApartmentManagement\Domain\Model\Apartment\AttributesCollection;
use App\ApartmentManagement\Domain\Model\Apartment\AttributeValuesMap;
use Prooph\EventSourcing\AggregateChanged;

final class ApartmentOccupied extends AggregateChanged
{
    public static function newInstance(
        ApartmentId $id,
        AttributeValuesMap $attributeValues,
        AttributesCollection $allAttributes
    ): self {

    }
}
