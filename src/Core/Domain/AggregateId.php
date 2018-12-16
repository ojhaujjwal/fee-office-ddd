<?php
declare(strict_types=1);


namespace App\Core\Domain;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class AggregateId implements ValueObjectInterface
{
    /**
     * @var UuidInterface
     */
    private $uuid;

    private function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @throws \Exception
     *
     * @return static
     */
    public static function generate(): self
    {
        return new static(Uuid::uuid4());
    }

    /**
     * @param string $uuidString
     * @return static
     */
    public static function fromString(string $uuidString): self
    {
        return new static(Uuid::fromString($uuidString));
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function sameValueAs($other): bool
    {
        return $other instanceof self && $this->uuid->equals($other->uuid);
    }
}
