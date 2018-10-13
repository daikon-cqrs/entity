<?php
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Daikon\Entity\Entity\Path;

use Ds\Vector;

final class ValuePath implements \IteratorAggregate, \Countable
{
    /**
     * @var Vector
     */
    private $internalVector;

    public function __construct(iterable $pathParts = null)
    {
        $this->internalVector = new Vector(
            (function (ValuePathPart ...$pathParts): array {
                return $pathParts;
            })(...$pathParts ?? new \ArrayIterator([]))
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

    public function getIterator(): \Traversable
    {
        return $this->internalVector->getIterator();
    }

    public function __toString(): string
    {
        $flattenPath = function (string $path, ValuePathPart $pathPart): string {
            return empty($path) ? (string)$pathPart : sprintf('%s-%s', $path, $pathPart);
        };
        return $this->internalVector->reduce($flattenPath, '');
    }

    public function __clone()
    {
        $this->internalVector = new Vector($this->internalVector->toArray());
    }
}
