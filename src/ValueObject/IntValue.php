<?php
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Daikon\Entity\ValueObject;

use Daikon\Entity\Assert\Assertion;
use Daikon\Interop\ValueObjectInterface;

final class IntValue implements ValueObjectInterface
{
    /** @var int|null */
    private $value;

    /** @param int|null $value  */
    public static function fromNative($value): IntValue
    {
        Assertion::nullOrIntegerish($value, 'Trying to create IntValue VO from unsupported value type.');
        return new self($value);
    }

    public function toNative(): ?int
    {
        return $this->value;
    }

    public function equals(ValueObjectInterface $value): bool
    {
        return $value instanceof self && $this->toNative() === $value->toNative();
    }

    public function __toString(): string
    {
        return is_null($this->value) ? 'null' : (string)$this->value;
    }

    private function __construct(?int $value)
    {
        $this->value = $value;
    }
}
