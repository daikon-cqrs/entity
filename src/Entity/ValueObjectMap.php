<?php

namespace Daikon\Entity\Entity;

use Daikon\DataStructure\TypedMapTrait;
use Daikon\Entity\ValueObject\Nil;
use Daikon\Entity\ValueObject\ValueObjectInterface;

final class ValueObjectMap implements \IteratorAggregate, \Countable
{
    use TypedMapTrait;

    /**
     * @var TypedEntityInterface $entity
     */
    private $entity;

    public static function forEntity(TypedEntityInterface $entity, array $entityState = []): self
    {
        return new static($entity, $entityState);
    }

    public function withValue(string $attrName, $value): self
    {
        $clonedMap = clone $this;
        $attribute = $this->entity->getEntityType()->getAttribute($attrName);
        $clonedMap->compositeMap[$attrName] = $attribute->makeValue($value, $clonedMap->entity);
        return $clonedMap;
    }

    public function withValues(array $values): self
    {
        $clonedMap = clone $this;
        foreach ($values as $attrName => $value) {
            $attribute = $clonedMap->entity->getEntityType()->getAttribute($attrName);
            $clonedMap->compositeMap[$attrName] = $attribute->makeValue($value, $clonedMap->entity);
        }
        return $clonedMap;
    }

    public function toArray(): array
    {
        $array = [];
        foreach ($this as $attributeName => $valueObject) {
            $array[$attributeName] = $valueObject->toNative();
        }
        return $array;
    }

    public function diff(ValueObjectMap $valueMap): ValueObjectMap
    {
        $clonedMap = clone $this;
        $clonedMap->compositeMap = $this->compositeMap->filter(
            function (string $attrName, ValueObjectInterface $value) use ($valueMap): bool {
                return !$valueMap->has($attrName) || !$value->equals($valueMap->get($attrName));
            }
        );
        return $clonedMap;
    }

    private function __construct(TypedEntityInterface $entity, array $values = [])
    {
        $this->entity = $entity;
        $valueObjects = [];
        foreach ($entity->getEntityType()->getAttributes() as $attrName => $attribute) {
            if (array_key_exists($attrName, $values)) {
                $valueObjects[$attrName] = $attribute->makeValue($values[$attrName], $this->entity);
            }
        }
        $this->init($valueObjects, ValueObjectInterface::class);
    }
}
