<?php
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Daikon\Entity\Entity;

use Daikon\ValueObject\ValueObjectInterface;

interface EntityListInterface extends ValueObjectInterface
{
    public static function makeEmpty(): EntityListInterface;

    public static function wrap($entities): EntityListInterface;

    public function diff(EntityListInterface $list): EntityListInterface;

    public function has(int $index): bool;

    public function get(int $index): EntityInterface;

    public function push(EntityInterface $item): EntityListInterface;

    public function unshift(EntityInterface $item): EntityListInterface;

    public function remove(EntityInterface $item): EntityListInterface;

    public function reverse(): EntityListInterface;

    public function count(): int;

    public function isEmpty(): bool;

    public function indexOf(EntityInterface $item);

    public function getFirst(): ?EntityInterface;

    public function getLast(): ?EntityInterface;

    public function unwrap(): array;

    public function getIterator(): \Iterator;
}
