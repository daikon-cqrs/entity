<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Entity;

use Daikon\Interop\Assertion;
use Daikon\ValueObject\ValueObjectMap;

final class EntityDiff
{
    public function __invoke(EntityInterface $left, EntityInterface $right): ValueObjectMap
    {
        Assertion::isInstanceOf($right, get_class($left), 'Comparing entities of different types is not supported.');

        return new ValueObjectMap(array_reduce(
            $left->getAttributeMap()->keys(),
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
        $left_val = $left->get($attribute, null);
        $right_val = $right->get($attribute, null);
        if (is_null($left_val)) {
            if (!is_null($right_val)) {
                $diff[$attribute] = $left->get($attribute);
            }
        } else {
            if (is_null($right_val) || !$left_val->equals($right_val)) {
                $diff[$attribute] = $left->get($attribute);
            }
        }
        return $diff;
    }
}
