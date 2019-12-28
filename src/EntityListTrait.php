<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Entity;

use Assert\Assertion;
use Ds\Vector;
use InvalidArgumentException;
use OutOfRangeException;
use Traversable;
use UnderflowException;

trait EntityListTrait
{
    /** @var Vector */
    private $compositeVector;

    /** @var string[] */
    private $itemTypes = [];

    public function has(int $index): bool
    {
        return $this->compositeVector->offsetExists($index);
    }

    /** @throws OutOfRangeException */
    public function get(int $index): EntityInterface
    {
        return $this->compositeVector->get($index);
    }

    /** @throws InvalidArgumentException */
    public function push(EntityInterface $item): EntityListInterface
    {
        $this->assertItemType($item);
        $copy = clone $this;
        $copy->compositeVector->push($item);
        return $copy;
    }

    /** @throws InvalidArgumentException */
    public function unshift(EntityInterface $item): EntityListInterface
    {
        $this->assertItemType($item);
        $copy = clone $this;
        $copy->compositeVector->unshift($item);
        return $copy;
    }

    /** @throws InvalidArgumentException */
    public function remove(EntityInterface $item): EntityListInterface
    {
        $index = $this->indexOf($item);
        if ($index === false) {
            return $this;
        }
        $copy = clone $this;
        $copy->compositeVector->remove((int)$index);
        return $copy;
    }

    public function replace(EntityInterface $item, EntityInterface $replacement): EntityListInterface
    {
        $index = $this->indexOf($item);
        if ($index === false) {
            throw new OutOfRangeException;
        }
        $copy = clone $this;
        $copy->compositeVector->remove((int)$index);
        $copy->compositeVector->insert((int)$index, $replacement);
        return $copy;
    }

    public function reverse(): EntityListInterface
    {
        $copy = clone $this;
        $copy->compositeVector->reverse();
        return $copy;
    }

    public function count(): int
    {
        return $this->compositeVector->count();
    }

    public function isEmpty(): bool
    {
        return $this->compositeVector->isEmpty();
    }

    /** @throws InvalidArgumentException */
    public function indexOf(EntityInterface $item)
    {
        $this->assertItemType($item);
        return $this->compositeVector->find($item);
    }

    /** @throws UnderflowException */
    public function getFirst(): EntityInterface
    {
        return $this->compositeVector->first();
    }

    /** @throws UnderflowException */
    public function getLast(): EntityInterface
    {
        return $this->compositeVector->last();
    }

    public function getIterator(): Traversable
    {
        return $this->compositeVector->getIterator();
    }

    public function getItemTypes(): array
    {
        return $this->itemTypes;
    }

    /** @param string|string[] $itemTypes */
    private function init(iterable $items, $itemTypes): void
    {
        if (!is_string($itemTypes) && !is_array($itemTypes)) {
            throw new InvalidArgumentException('Item types must be a string or array.');
        }
        $this->itemTypes = (array)$itemTypes;
        foreach ($items as $index => $item) {
            $this->assertItemIndex($index);
            $this->assertItemType($item);
        }
        $this->compositeVector = new Vector($items);
    }

    /** @param mixed $index */
    private function assertItemIndex($index): void
    {
        if (!is_int($index)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid item key given to %s. Expected int but was given %s.',
                static::class,
                is_object($index) ? get_class($index) : @gettype($index)
            ));
        }
    }

    /** @param EntityInterface $item */
    private function assertItemType($item): void
    {
        $itemIsValid = false;
        foreach ($this->itemTypes as $type) {
            if (is_a($item, $type, true)) {
                $itemIsValid = true;
                break;
            }
        }
        if (!$itemIsValid) {
            throw new InvalidArgumentException(sprintf(
                'Invalid item type given to %s. Expected one of %s but was given %s.',
                static::class,
                implode(', ', $this->itemTypes),
                is_object($item) ? get_class($item) : @gettype($item)
            ));
        }
    }

    private function __clone()
    {
        $this->compositeVector = new Vector($this->compositeVector->toArray());
    }

    public static function makeEmpty(): EntityListInterface
    {
        return new static;
    }

    public static function wrap(iterable $entities): EntityListInterface
    {
        return new static($entities);
    }

    public function unwrap(): array
    {
        return $this->compositeVector->toArray();
    }

    /** @param null|iterable $payload */
    public static function fromNative($payload): EntityListInterface
    {
        Assertion::nullOrIsTraversable($payload);

        if (is_null($payload)) {
            return static::makeEmpty();
        }
        $entities = [];
        foreach ($payload as $entity) {
            Assertion::keyExists($entity, EntityInterface::TYPE_KEY);
            $entityType = $entity[EntityInterface::TYPE_KEY];
            $entities[] = call_user_func([$entityType, 'fromNative'], $entity);
        }
        return static::wrap($entities);
    }

    public function toNative(): array
    {
        return $this->compositeVector->map(function (EntityInterface $entity): array {
            return (array)$entity->toNative();
        })->toArray();
    }

    /** @param self $comparator */
    public function equals($comparator): bool
    {
        if (!$comparator instanceof static || $this->count() !== $comparator->count()) {
            return false;
        }
        /** @var EntityInterface $entity */
        foreach ($this->compositeVector as $index => $entity) {
            if (!$entity->equals($comparator->get($index))) {
                return false;
            }
        }
        return true;
    }

    public function diff(EntityListInterface $list): EntityListInterface
    {
        $differingEntities = [];
        foreach ($this->compositeVector as $pos => $entity) {
            if (!$list->has($pos) || !(new EntityDiff)($entity, $list->get($pos))->isEmpty()) {
                $differingEntities[] = $entity;
            }
        }
        return new static($differingEntities);
    }

    public function __toString(): string
    {
        $parts = [];
        foreach ($this as $entity) {
            $parts[] = (string)$entity;
        }
        return implode(', ', $parts);
    }
}
