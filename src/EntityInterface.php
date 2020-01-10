<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Entity;

use Daikon\ValueObject\ValueObjectInterface;

interface EntityInterface extends ValueObjectInterface
{
    public const TYPE_KEY = '@type';

    public static function getAttributeMap(): AttributeMap;

    public function getIdentity(): ValueObjectInterface;

    /** @param static $entity */
    public function isSameAs(EntityInterface $entity): bool;

    public function has(string $name): bool;

    /** @param null|ValueObjectInterface $default */
    public function get(string $name, $default = null): ?ValueObjectInterface;

    /** @param mixed $value */
    public function withValue(string $name, $value): self;

    public function withValues(iterable $values): self;
}
