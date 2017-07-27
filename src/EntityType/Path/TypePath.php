<?php

namespace Daikon\Entity\EntityType\Path;

use Daikon\Entity\EntityType\AttributeInterface;
use Ds\Vector;

final class TypePath implements \IteratorAggregate, \Countable
{
   /**
     * @var Vector
     */
    private $internalVector;

    /**
     * Returns attribute path of this attribute. Depending on this attribute
     * being part of a nested-entity this may look like this format:
     * {attribute_name}.{type_prefix}.{attribute_name}
     * @param AttributeInterface $attribute
     * @return TypePath
     */
    public static function fromAttribute(AttributeInterface $attribute): self
    {
        $currentAttribute = $attribute->getParent();
        $currentType = $attribute->getEntityType();
        $pathLeaf = new TypePathPart($attribute->getName());
        $typePath = new TypePath([ $pathLeaf ]);
        while ($currentAttribute) {
            $pathPart = new TypePathPart($currentAttribute->getName(), $currentType->getName());
            $typePath = $typePath->push($pathPart);
            $currentAttribute = $currentAttribute->getParent();
            if ($currentAttribute) {
                $currentType = $currentAttribute->getEntityType();
            }
        }
        return $typePath->reverse();
    }

    public function __construct(iterable $pathParts = null)
    {
        $this->internalVector = new Vector(
            (function (TypePathPart ...$pathParts): array {
                return $pathParts;
            })(...$pathParts ?? [])
        );
    }

    public function push(TypePathPart $pathPart): TypePath
    {
        $clonedPath = clone $this;
        $clonedPath->internalVector->push($pathPart);
        return $clonedPath;
    }

    public function reverse(): TypePath
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
        $flattenPath = function (string $path, TypePathPart $pathPart): string {
            return empty($path) ? (string)$pathPart : "$path-$pathPart";
        };
        return $this->internalVector->reduce($flattenPath, "");
    }

    public function __clone()
    {
        $this->internalVector = new Vector($this->internalVector->toArray());
    }
}
