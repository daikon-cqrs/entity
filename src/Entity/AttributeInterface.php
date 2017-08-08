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

interface AttributeInterface
{
    public static function define(string $name, $valueType): AttributeInterface;

    public function makeValue($value = null): ValueObjectInterface;

    public function getName(): string;

    public function getValueType();
}
