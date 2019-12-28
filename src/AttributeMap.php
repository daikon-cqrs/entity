<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Entity;

use Countable;
use Daikon\DataStructure\TypedMapTrait;
use InvalidArgumentException;
use IteratorAggregate;

final class AttributeMap implements IteratorAggregate, Countable
{
    use TypedMapTrait;

    public function __construct(iterable $attributes = [])
    {
        $mappedAttributes = [];
        /** @var AttributeInterface $attribute */
        foreach ($attributes as $attribute) {
            $attributeName = $attribute->getName();
            if (isset($mappedAttributes[$attributeName])) {
                throw new InvalidArgumentException("Attribute name '$attributeName' is already defined.");
            }
            $mappedAttributes[$attributeName] = $attribute;
        }

        $this->init($mappedAttributes, AttributeInterface::class);
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
