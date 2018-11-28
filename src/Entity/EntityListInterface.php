<?php
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Daikon\Entity\Entity;

use Daikon\Entity\ValueObject\ValueObjectInterface;
use Iterator;

interface EntityListInterface extends ValueObjectInterface
{
    public static function makeEmpty();

    public static function wrap($entities);

    public function diff(EntityListInterface $list): EntityListInterface;

    public function has(int $index): bool;

    public function get(int $index);

    public function push(EntityInterface $item);

    public function prepend(EntityInterface $item);

    public function remove(EntityInterface $item);

    public function reverse();

    public function count(): int;

    public function isEmpty(): bool;

    public function indexOf(EntityInterface $item): int;

    public function getFirst();

    public function getLast();

    public function unwrap(): array;

    public function getIterator(): Iterator;
}
