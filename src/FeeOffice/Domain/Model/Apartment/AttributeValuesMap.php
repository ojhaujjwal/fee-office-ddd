<?php


namespace App\FeeOffice\Domain\Model\Apartment;


final class AttributeValuesMap implements \IteratorAggregate, \Countable
{
    private $map = [];

    public function __construct(AttributeValue... $attributeValues)
    {
        foreach ($attributeValues as $attributeValue) {
            $this->map[$attributeValue->attributeId()->toString()] = $attributeValue->value();
        }
    }

    public function has(AttributeId $attributeId): bool
    {
        return array_key_exists($attributeId->toString(), $this->map);
    }

    public function get(AttributeId $attributeId)
    {
        //TODO: throw exception if not exists
        return $this->map[$attributeId->toString()];
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->map);
    }

    public function count(): int
    {
        return count($this->map);
    }

    public function toArray(): array
    {
        return $this->map;
    }

    public function without(AttributeId... $attributeIds): AttributeValuesMap
    {
        $new = new self();
        $new->map = $this->map;
        foreach ($attributeIds as $attributeId) {
            unset($new->map[$attributeId->toString()]);
        }
        return $new;
    }
}
