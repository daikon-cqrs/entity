<?php
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Daikon\Entity\Entity;

use Daikon\DataStructure\TypedMapTrait;

final class AttributeMap implements \IteratorAggregate, \Countable
{
    use TypedMapTrait;

    public function __construct(array $attributes = [])
    {
        $this->init(array_reduce($attributes, function (array $carry, AttributeInterface $attribute): array {
            $carry[$attribute->getName()] = $attribute; // enforce consistent attribute keys
            return $carry;
        }, []), AttributeInterface::class);
    }

    public function byClassNames(array $classNames = []): self
    {
        $clonedMap = clone $this;
        (function (string ...$classNames) use ($clonedMap): void {
            $clonedMap->compositeMap = $clonedMap->compositeMap->filter(
                function (string $name, AttributeInterface $attribute) use ($classNames): bool {
                    return in_array(get_class($attribute), $classNames);
                }
            );
        })(...$classNames);
        return $clonedMap;
    }
}
