<?php
declare(strict_types=1);

namespace App\ApartmentManagement\Domain\Model\Apartment;

use App\Core\Domain\Enum;

final class AttributeRequirementSpec extends Enum
{
    const MANDATORY = 1;
    const MANDATORY_FOR_OCCUPIED_APARTMENT = 2;
    const OPTIONAL = 3;
}
