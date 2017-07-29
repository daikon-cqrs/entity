<?php

namespace Daikon\Entity\Entity;

use Daikon\Entity\Assert\Assertion;
use Daikon\Entity\EntityType\AttributeInterface;
use Daikon\Entity\EntityType\EntityTypeInterface;
use Daikon\Entity\Exception\UnexpectedType;

final class EntityDiff
{
    public function __invoke(EntityInterface $left, EntityInterface $right): ValueObjectMap
    {
        $this->assertComparabiliy($left, $right);
        return ValueObjectMap::forEntity($left, array_reduce(
            $this->listAtrributeNames($left->getEntityType()),
            function (array $diff, string $attribute) use ($left, $right): array {
                if ($this->bothEntitesHaveValueSet($attribute, $left, $right)) {
                    $diff = $this->addValueIfDifferent($diff, $attribute, $left, $right);
                } elseif ($left->has($attribute)) {
                    $diff[$attribute] = $left->get($attribute);
                }
                return $diff;
            },
            []
        ));
    }

    private function assertComparabiliy(EntityInterface $left, EntityInterface $right)
    {
        Assertion::isInstanceOf(
            $right->getEntityType(),
            get_class($left->getEntityType()),
            'Comparing entities of different types is not supported.'
        );
    }

    private function listAtrributeNames(EntityTypeInterface $entityType): array
    {
        return array_keys($entityType->getAttributes()->toArray());
    }

    private function bothEntitesHaveValueSet(string $attribute, EntityInterface $left, EntityInterface $right): bool
    {
        return $left->has($attribute) && $right->has($attribute);
    }

    private function addValueIfDifferent(array $diff, string $attribute, EntityInterface $left, $right): array
    {
        if (!$left->get($attribute)->equals($right->get($attribute))) {
            $diff[$attribute] = $left->get($attribute);
        }
        return $diff;
    }
}
