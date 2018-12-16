<?php
declare(strict_types=1);

namespace App\FeeOffice\Domain\Event;

use App\FeeOffice\Domain\Model\Apartment\ApartmentId;
use App\FeeOffice\Domain\Model\Apartment\AttributesCollection;
use App\FeeOffice\Domain\Model\Apartment\AttributeValuesMap;
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
