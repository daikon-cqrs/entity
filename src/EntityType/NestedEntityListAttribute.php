<?php

namespace Daikon\Entity\EntityType;

use Daikon\Entity\Assert\Assert;
use Daikon\Entity\Entity\EntityInterface;
use Daikon\Entity\Entity\NestedEntityList;
use Daikon\Entity\Entity\TypedEntityInterface;
use Daikon\Entity\ValueObject\ValueObjectInterface;

class NestedEntityListAttribute extends NestedEntityAttribute
{
    public function makeValue($value = null, EntityInterface $parent = null): ValueObjectInterface
    {
        if ($value instanceof NestedEntityList) {
            foreach ($value as $entity) {
                parent::makeValue($entity); // will check for type compliance
            }
            return $value;
        }
        Assert::that($value)->nullOr()->isArray();
        return is_null($value) ? NestedEntityList::makeEmpty() : $this->makeEntityList($value, $parent);
    }

    private function makeEntityList(array $values, TypedEntityInterface $parentEntity = null): NestedEntityList
    {
        return NestedEntityList::wrap(
            array_map(function (array $entityValues) use ($parentEntity) {
                return parent::makeValue($entityValues, $parentEntity);
            }, $values)
        );
    }
}
