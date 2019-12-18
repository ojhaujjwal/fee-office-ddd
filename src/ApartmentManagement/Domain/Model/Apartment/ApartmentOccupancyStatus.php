<?php
declare(strict_types=1);

namespace App\ApartmentManagement\Domain\Model\Apartment;

use App\Core\Domain\Enum;

final class ApartmentOccupancyStatus extends Enum
{
    const OCCUPIED = true;
    const VACANT = false;
}
