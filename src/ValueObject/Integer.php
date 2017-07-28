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
     * @return Integer
     */
    public static function fromNative($nativeValue): Integer
    {
        Assertion::nullOrInteger($nativeValue);
        return new Integer($nativeValue);
    }

    public function toNative(): ?int
    {
        return $this->value;
    }

    public function equals(ValueObjectInterface $otherValue): bool
    {
        return $otherValue instanceof Integer && $this->toNative() === $otherValue->toNative();
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
