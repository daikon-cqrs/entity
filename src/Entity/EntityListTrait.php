<?php
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Daikon\Entity\Entity;

use Assert\Assertion;
use Daikon\Entity\ValueObject\ValueObjectInterface;
use Ds\Vector;
use Iterator;

trait EntityListTrait
{
    /** @var Vector */
    private $compositeVector;

    public static function makeEmpty(): self
    {
        return new self;
    }

    public static function wrap($entities): self
    {
        return new self($entities);
    }

    public static function fromNative($payload): self
    {
        Assertion::nullOrIsArray($payload);
        if (is_null($payload)) {
            return self::makeEmpty();
        }
        $entities = [];
        foreach ($payload as $entity) {
            Assertion::keyExists($entity, EntityInterface::TYPE_KEY);
            $entityFqcn = $entity[EntityInterface::TYPE_KEY];
            $entities[] = call_user_func([ $entityFqcn, 'fromNative' ], $entity);
        }
        return self::wrap($entities);
    }

    public function toNative(): array
    {
        return $this->compositeVector->map(function (ValueObjectInterface $entity): array {
            return $entity->toNative();
        })->toArray();
    }

    public function equals(ValueObjectInterface $list): bool
    {
        /** EntityList $list */
        if (!$list instanceof self || $this->count() !== $list->count()) {
            return false;
        }
        /** @var  EntityInterface $entity */
        foreach ($this as $pos => $entity) {
            if (!$entity->equals($list->get($pos))) {
                return false;
            }
        }
        return true;
    }

    public function __toString(): string
    {
        $parts = [];
        foreach ($this as $entity) {
            $parts[] = (string)$entity;
        }
        return implode(', ', $parts);
    }

    public function diff(EntityListInterface $list): EntityListInterface
    {
        $differingEntities = [];
        foreach ($this as $pos => $entity) {
            if (!$list->has($pos) || !(new EntityDiff)($entity, $list->get($pos))->isEmpty()) {
                $differingEntities[] = $entity;
            }
        }
        return new self($differingEntities);
    }

    public function has(int $index): bool
    {
        return $this->compositeVector->offsetExists($index);
    }

    public function get(int $index): EntityInterface
    {
        return $this->compositeVector->get($index);
    }

    public function push(EntityInterface $item): self
    {
        $copy = clone $this;
        $copy->compositeVector->push($item);
        return $copy;
    }

    public function prepend(EntityInterface $item): self
    {
        $copy = clone $this;
        $copy->compositeVector->unshift($item);
        return $copy;
    }

    public function remove(EntityInterface $item): self
    {
        $idx = $this->indexOf($item);
        $copy = clone $this;
        $copy->compositeVector->remove($idx);
        return $copy;
    }

    public function reverse(): self
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

    public function indexOf(EntityInterface $item): int
    {
        return $this->compositeVector->find($item);
    }

    public function getFirst()
    {
        return $this->compositeVector->first();
    }

    public function getLast()
    {
        return $this->compositeVector->last();
    }

    public function unwrap(): array
    {
        return $this->compositeVector->toArray();
    }

    public function getIterator(): Iterator
    {
        return $this->compositeVector->getIterator();
    }
}
