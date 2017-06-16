<?php

namespace Accordia\Entity\EntityType;

use Accordia\Entity\Assert\Assert;
use Accordia\Entity\Assert\Assertion;
use Accordia\Entity\Entity\EntityInterface;
use Accordia\Entity\Entity\NestedEntityList;
use Accordia\Entity\Entity\TypedEntityInterface;
use Accordia\Entity\ValueObject\ValueObjectInterface;

class NestedEntityListAttribute extends NestedEntityAttribute
{
    /**
     * {@inheritdoc}
     */
    public function makeValue($value = null, EntityInterface $parent = null): ValueObjectInterface
    {
        if ($value instanceof NestedEntityList) {
            foreach ($value as $entity) {
                parent::makeValue($entity); // will check for type compliance
            }
            return $value;
        }
        Assert::that($value)->nullOr()->isArray();
        return is_null($value) ? new NestedEntityList : $this->makeEntityList($value, $parent);
    }

    /**
     * @param array $values
     * @param TypedEntityInterface $parentEntity
     * @return Vector
     */
    private function makeEntityList(array $values, TypedEntityInterface $parentEntity = null): NestedEntityList
    {
        return new NestedEntityList(
            array_map(function (array $entityValues) use ($parentEntity) {
                return parent::makeValue($entityValues, $parentEntity);
            }, $values)
        );
    }
}
