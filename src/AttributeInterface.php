<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Entity;

use Daikon\ValueObject\ValueObjectInterface;

interface AttributeInterface
{
    public static function define(string $name, string $valueType): self;

    /** @psalm-suppress MissingParamType */
    public function makeValue($value = null): ValueObjectInterface;

    public function getName(): string;

    public function getValueType(): string;
}
