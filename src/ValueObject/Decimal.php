<?php

namespace Daikon\Entity\ValueObject;

use Daikon\Entity\Assert\Assertion;

final class Decimal implements ValueObjectInterface
{
    /**
     * @var null
     */
    private const NIL = null;

    /**
     * @var float
     */
    private $value;

    public static function fromNative($nativeValue): Decimal
    {
        Assertion::nullOrFloat($nativeValue, 'Trying to create Decimal VO from unsupported value type.');
        return is_float($nativeValue) ? new Decimal($nativeValue) : new Decimal;
    }

    public function toNative(): ?float
    {
        return $this->value;
    }

    public function equals(ValueObjectInterface $value): bool
    {
        return $value instanceof Decimal && $this->toNative() === $value->toNative();
    }

    public function __toString(): string
    {
        return $this->value ? (string)$this->value : 'null';
    }

    private function __construct(?float $value = null)
    {
        $this->value = $value;
    }
}
