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
use Daikon\Interop\ToNativeInterface;
use Daikon\ValueObject\ValueObjectInterface;
use IteratorAggregate;

final class ValueObjectMap implements IteratorAggregate, Countable, ToNativeInterface
{
    use TypedMapTrait;

    /** @var EntityInterface */
    private $entity;

    public static function forEntity(EntityInterface $entity, array $entityState = []): self
    {
        return new self($entity, $entityState);
    }

    /** @param mixed $value */
    public function withValue(string $attrName, $value): self
    {
        $clonedMap = clone $this;
        $attribute = $this->entity->getAttributeMap()->get($attrName);
        $clonedMap->compositeMap[$attrName] = $attribute->makeValue($value, $clonedMap->entity);
        return $clonedMap;
    }

    public function withValues(array $values): self
    {
        $clonedMap = clone $this;
        foreach ($values as $attrName => $value) {
            $attribute = $clonedMap->entity->getAttributeMap()->get($attrName);
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
        foreach ($entity->getAttributeMap() as $attrName => $attribute) {
            if (array_key_exists($attrName, $values)) {
                $valueObjects[$attrName] = $attribute->makeValue($values[$attrName], $this->entity);
            }
        }
        $this->init($valueObjects, ValueObjectInterface::class);
    }
}
