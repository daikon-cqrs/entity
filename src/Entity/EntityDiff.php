<?php

namespace Daikon\Entity\Entity;

use Daikon\Entity\EntityType\AttributeInterface;
use Daikon\Entity\Exception\UnexpectedType;

final class EntityDiff
{
    public function __invoke(EntityInterface $leftEntity, EntityInterface $rightEntity): ValueObjectMap
    {
        $this->assertComparabiliy($leftEntity, $rightEntity);
        return $this->generateDiff($leftEntity, $rightEntity);
    }

    private function assertComparabiliy(EntityInterface $leftEntity, EntityInterface $rightEntity)
    {
        if ($leftEntity->getEntityType() !== $rightEntity->getEntityType()) {
            throw new UnexpectedType('Comparing entities of different types is not supported.');
        }
    }

    private function generateDiff(EntityInterface $leftEntity, EntityInterface $rightEntity): ValueObjectMap
    {
        return ValueObjectMap::forEntity($leftEntity, array_reduce(
            $leftEntity->getEntityType()->getAttributes()->toArray(),
            function (array $diff, AttributeInterface $attribute) use ($leftEntity, $rightEntity): array {
                $attrName = $attribute->getName();
                if ($rightEntity->has($attrName) && $leftEntity->has($attrName)) {
                    if (!$leftEntity->get($attrName)->equals($rightEntity->get($attrName))) {
                        $diff[$attrName] = $leftEntity->get($attrName);
                    }
                } elseif ($leftEntity->has($attrName)) {
                    $diff[$attrName] = $leftEntity->get($attrName);
                }
                return $diff;
            },
            []
        ));
    }
}
