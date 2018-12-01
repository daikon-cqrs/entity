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

interface EntityInterface extends ValueObjectInterface
{
    public const TYPE_KEY = '@type';

    public static function getAttributeMap(): AttributeMap;

    public function getIdentity(): ValueObjectInterface;

    public function isSameAs(EntityInterface $entity): bool;

    public function withValue(string $attributeName, $value): EntityInterface;

    public function withValues(array $values): EntityInterface;

    public function get(string $valuePath): ?ValueObjectInterface;

    public function has(string $attributeName): bool;
}
