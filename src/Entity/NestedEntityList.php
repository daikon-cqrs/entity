<?php

namespace Daikon\Entity\Entity;

use Daikon\DataStructure\TypedListTrait;
use Daikon\Entity\ValueObject\ValueObjectInterface;
use Daikon\Entity\ValueObject\ValueObjectListInterface;

final class NestedEntityList implements ValueObjectListInterface
{
    use TypedListTrait;

    public static function makeEmpty(): self
    {
        return new self;
    }

    public static function wrap($entities): self
    {
        return new self($entities);
    }

    public static function fromNative($nativeValue): self
    {
        // @todo implement
    }

    public function toNative(): array
    {
        return $this->compositeVector->map(static function (ValueObjectInterface $entity): array {
            return $entity->toNative();
        })->toArray();
    }

    public function equals(ValueObjectInterface $otherList): bool
    {
        /** NestedEntityList $otherList */
        if (!$otherList instanceof self) {
            return false;
        }
        if (count($this) !== count($otherList)) {
            return false;
        }
        foreach ($this as $pos => $value) {
            if (!$value->equals($otherList->get($pos))) {
                return false;
            }
        }
        return true;
    }

    public function __toString(): string
    {
        $parts = [];
        foreach ($this as $nestedEntity) {
            $parts[] = (string)$nestedEntity;
        }
        return implode(', ', $parts);
    }

    public function diff(ValueObjectListInterface $otherList): ValueObjectListInterface
    {
        $differentEntities = [];
        /* @var EntityInterface $entity */
        foreach ($this as $pos => $entity) {
            if (!$otherList->has($pos)) {
                $differentEntities[] = $entity;
                continue;
            }
            /* @var EntityInterface $otherEntity */
            $otherEntity = $otherList->get($pos);
            $diff = (new EntityDiff)($entity, $otherEntity);
            if (!$diff->isEmpty()) {
                $differentEntities[] = $entity;
            }
        }
        return new static($differentEntities);
    }

    private function __construct(array $entities = [])
    {
        $this->init($entities, EntityInterface::class);
    }
}
