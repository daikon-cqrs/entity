<?php

namespace Daikon\Entity\Entity\Path;

use Daikon\Entity\Assert\Assertion;
use Daikon\Entity\Entity\NestedEntity;
use Daikon\Entity\Entity\NestedEntityList;
use Daikon\Entity\Entity\TypedEntityInterface;
use Ds\Vector;

final class ValuePath implements \IteratorAggregate, \Countable
{
    /**
     * @var Vector
     */
    private $internalVector;

    public static function fromEntity(TypedEntityInterface $entity): self
    {
        $parentEntity = $entity->getEntityParent();
        $currentEntity = $entity;
        $valuePath = new ValuePath;
        while ($parentEntity) {
            /* @var NestedEntity $currentEntity */
            Assertion::isInstanceOf($currentEntity, NestedEntity::class);
            $attributeName = $currentEntity->getEntityType()->getParentAttribute()->getName();
            /* @var NestedEntityList $entityList */
            $entityList = $parentEntity->get($attributeName);
            $entityPos = $entityList->indexOf($currentEntity);
            $valuePath = $valuePath->push(new ValuePathPart($attributeName, $entityPos));
            $currentEntity = $parentEntity;
            $parentEntity = $parentEntity->getEntityParent();
        }
        return $valuePath->reverse();
    }

    public function __construct(iterable $pathParts = null)
    {
        $this->internalVector = new Vector(
            (function (ValuePathPart ...$pathParts): array {
                return $pathParts;
            })(...$pathParts ?? [])
        );
    }

    public function push(ValuePathPart $pathPart): ValuePath
    {
        $clonedPath = clone $this;
        $clonedPath->internalVector->push($pathPart);
        return $clonedPath;
    }

    public function reverse(): ValuePath
    {
        $clonedPath = clone $this;
        $clonedPath->internalVector->reverse();
        return $clonedPath;
    }

    public function count(): int
    {
        return count($this->internalVector);
    }

    public function getIterator(): \Iterator
    {
        return $this->internalVector->getIterator();
    }

    public function __toString(): string
    {
        $flattenPath = function (string $path, ValuePathPart $pathPart): string {
            return empty($path) ? (string)$pathPart : "$path-$pathPart";
        };
        return $this->internalVector->reduce($flattenPath, "");
    }

    public function __clone()
    {
        $this->internalVector = new Vector($this->internalVector->toArray());
    }
}
