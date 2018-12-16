<?php
declare(strict_types=1);

namespace App\Core\Domain;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;

abstract class AbstractDomainCommand extends Command
{
    use PayloadTrait;
}
