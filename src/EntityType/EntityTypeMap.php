<?php

namespace Daikon\Entity\EntityType;

use Daikon\DataStructure\TypedMapTrait;

final class EntityTypeMap implements \IteratorAggregate, \Countable
{
    use TypedMapTrait;

    public function __construct(array $entityTypes = [])
    {
        $this->init(array_reduce($entityTypes, function (array $carry, EntityTypeInterface $entityType) {
            $carry[$entityType->getName()] = $entityType; // enforce consistent attribute keys
            return $carry;
        }, []), EntityTypeInterface::class);
    }

    public function byName(string $name): ?EntityTypeInterface
    {
        foreach ($this as $type) {
            if ($type->getName() === $name) {
                return $type;
            }
        }
        return null;
    }

    public function byClassName(string $className): ?EntityTypeInterface
    {
        foreach ($this as $type) {
            if (get_class($type) === $className) {
                return $type;
            }
        }
        return null;
    }
}
