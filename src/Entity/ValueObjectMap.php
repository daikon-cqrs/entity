<?php

namespace Daikon\Entity\Entity;

use Countable;
use Daikon\DataStructure\TypedMapTrait;
use Daikon\Entity\ValueObject\Nil;
use Daikon\Entity\ValueObject\ValueObjectInterface;
use Daikon\Interop\ToNativeInterface;
use IteratorAggregate;

final class ValueObjectMap implements ToNativeInterface, IteratorAggregate, Countable
{
    use TypedMapTrait;

    /**
     * @var EntityInterface $entity
     */
    private $entity;

    public static function forEntity(EntityInterface $entity, array $entityState = []): self
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

    public function toNative(): array
    {
        $array = [];
        foreach ($this as $attributeName => $valueObject) {
            $array[$attributeName] = $valueObject->toNative();
        }
        return $array;
    }

    private function __construct(EntityInterface $entity, array $values = [])
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
