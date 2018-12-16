<?php
declare(strict_types=1);

namespace App\FeeOffice\Domain\Command;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;

final class AddBuildingWithApartments extends Command
{
    use PayloadTrait;
}
