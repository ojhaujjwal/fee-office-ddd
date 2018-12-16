<?php
declare(strict_types=1);

namespace App\FeeOffice\Domain\Model\Entrance;

final class Entrance
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Address
     */
    private $address;

    public function __construct(int $id, Address $address)
    {
        $this->id = $id;
        $this->address = $address;
    }
}
