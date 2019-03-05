<?php

/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types = 1);

namespace Daikon\Entity\ValueObject;

use Daikon\Entity\Assert\Assertion;
use Daikon\Interop\ValueObjectInterface;

final class FloatValue implements ValueObjectInterface
{
    /** @var float|null */
    private $value;

    /** @param float|string|null $value */
    public static function fromNative($value): FloatValue
    {
        $value = $value === '' ? null : $value;
        Assertion::nullOrFloat($value, 'Trying to create FloatValue VO from unsupported value type.');
        /** @psalm-suppress PossiblyInvalidArgument */
        return new self($value);
    }

    public function toNative(): ?float
    {
        return $this->value;
    }

    public function equals(ValueObjectInterface $value): bool
    {
        return $value instanceof self && $this->toNative() === $value->toNative();
    }

    public function __toString(): string
    {
        return $this->value ? (string) $this->value : 'null';
    }

    private function __construct(?float $value)
    {
        $this->value = $value;
    }
}
