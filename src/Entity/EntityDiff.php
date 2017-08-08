<?php
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Daikon\Entity\Entity;

use Daikon\Entity\Assert\Assertion;

final class EntityDiff
{
    public function __invoke(EntityInterface $left, EntityInterface $right): ValueObjectMap
    {
        $this->assertComparabiliy($left, $right);
        return ValueObjectMap::forEntity($left, array_reduce(
            $this->listAtrributeNames($left),
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
            $right,
            get_class($left),
            'Comparing entities of different types is not supported.'
        );
    }

    private function listAtrributeNames(EntityInterface $entity): array
    {
        return array_keys($entity->getAttributeMap()->toArray());
    }

    private function bothEntitesHaveValueSet(string $attribute, EntityInterface $left, EntityInterface $right): bool
    {
        return $left->has($attribute) && $right->has($attribute);
    }

    private function addValueIfDifferent(
        array $diff,
        string $attribute,
        EntityInterface $left,
        EntityInterface $right
    ): array {
        if (!$left->get($attribute)->equals($right->get($attribute))) {
            $diff[$attribute] = $left->get($attribute);
        }
        return $diff;
    }
}
