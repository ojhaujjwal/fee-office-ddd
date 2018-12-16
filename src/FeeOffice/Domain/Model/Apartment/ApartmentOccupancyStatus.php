<?php
declare(strict_types=1);

namespace App\FeeOffice\Domain\Model\Apartment;

use App\Core\Domain\Enum;

final class ApartmentOccupancyStatus extends Enum
{
    const OCCUPIED = true;
    const VACANT = false;
}
