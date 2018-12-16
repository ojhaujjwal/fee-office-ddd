<?php
declare(strict_types=1);

namespace App\FeeOffice\Domain\Event;

use App\FeeOffice\Domain\Model\Apartment\ApartmentId;
use App\FeeOffice\Domain\Model\Apartment\Attribute;
use App\FeeOffice\Domain\Model\Apartment\AttributeId;
use App\FeeOffice\Domain\Model\Apartment\AttributeRequirementSpec;
use App\FeeOffice\Domain\Model\Apartment\AttributesCollection;
use Prooph\EventSourcing\AggregateChanged;

final class ApartmentVacated extends AggregateChanged
{
    public static function newInstance(
        ApartmentId $id,
        AttributesCollection $allAttributes
    ): self {
        $unneededAttributes = $allAttributes->filterByRequirementSpec(
            AttributeRequirementSpec::get(AttributeRequirementSpec::MANDATORY_FOR_OCCUPIED_APARTMENT)
        );

        return self::occur($id->toString(), [
            'removedAttributes' => array_map(function(Attribute $attribute) {
                return $attribute->id()->toString();
            }, iterator_to_array($unneededAttributes))
        ]);
    }

    /**
     * @return AttributeId[]
     */
    public function removedAttributeIds(): array
    {
        return array_map(function($id) {
            return AttributeId::fromString($id);
        }, $this->payload()['removedAttributes']);
    }
}
