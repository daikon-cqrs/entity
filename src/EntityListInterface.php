<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Entity;

use Countable;
use Daikon\ValueObject\ValueObjectInterface;
use IteratorAggregate;
use Traversable;

interface EntityListInterface extends ValueObjectInterface, IteratorAggregate, Countable
{
    public static function makeEmpty(): EntityListInterface;

    public static function wrap(iterable $entities): EntityListInterface;

    public function diff(EntityListInterface $list): EntityListInterface;

    public function has(int $index): bool;

    public function get(int $index): EntityInterface;

    public function push(EntityInterface $item): EntityListInterface;

    public function unshift(EntityInterface $item): EntityListInterface;

    public function remove(EntityInterface $item): EntityListInterface;

    public function reverse(): EntityListInterface;

    public function count(): int;

    public function isEmpty(): bool;

    /** @return mixed */
    public function indexOf(EntityInterface $item);

    public function getFirst(): EntityInterface;

    public function getLast(): EntityInterface;

    public function unwrap(): array;

    public function getIterator(): Traversable;
}
