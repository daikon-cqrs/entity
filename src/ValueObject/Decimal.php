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

    public static function fromNative($nativeValue): self
    {
        Assertion::nullOrFloat($nativeValue, "Trying to create value from invalid value.");
        return is_float($nativeValue) ? new static($nativeValue) : new self;
    }

    public function toNative(): ?float
    {
        return $this->value;
    }

    public function equals(ValueObjectInterface $otherValue): bool
    {
        return $otherValue instanceof self && $this->toNative() === $otherValue->toNative();
    }

    public function __toString(): string
    {
        return $this->value ? (string)$this->value : "null";
    }

    private function __construct(?float $value = null)
    {
        $this->value = $value;
    }
}
