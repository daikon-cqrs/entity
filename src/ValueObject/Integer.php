<?php

namespace Daikon\Entity\ValueObject;

use Daikon\Entity\Assert\Assertion;

final class Integer implements ValueObjectInterface
{
    /**
     * @var null
     */
    private const NIL = null;

    /**
     * @var int
     */
    private $value;

    /**
     * @param int|null $nativeValue
     * @return self
     */
    public static function fromNative($nativeValue): self
    {
        Assertion::nullOrInteger($nativeValue);
        return new self($nativeValue);
    }

    public function toNative(): ?int
    {
        return $this->value;
    }

    public function equals(ValueObjectInterface $otherValue): bool
    {
        return $otherValue instanceof self && $this->toNative() === $otherValue->toNative();
    }

    public function __toString(): string
    {
        return $this->value === self::NIL ? "null" : (string)$this->value;
    }

    private function __construct(?int $value)
    {
        $this->value = $value;
    }
}
