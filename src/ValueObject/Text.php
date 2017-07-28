<?php

namespace Daikon\Entity\ValueObject;

use Daikon\Entity\Assert\Assertion;

final class Text implements ValueObjectInterface
{
    /**
     * @var string
     */
    private const NIL = "";

    /**
     * @var string
     */
    private $value;

    /**
     * @param string|null $nativeValue
     * @return Text
     */
    public static function fromNative($nativeValue): Text
    {
        Assertion::nullOrString($nativeValue);
        return is_null($nativeValue) ? new Text : new Text($nativeValue);
    }

    public function equals(ValueObjectInterface $otherValue): bool
    {
        return $otherValue instanceof Text && $this->toNative() === $otherValue->toNative();
    }

    public function toNative(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->toNative();
    }

    public function isEmpty(): bool
    {
        return $this->value === self::NIL;
    }

    public function getLength(): int
    {
        return strlen($this->value);
    }

    private function __construct(string $value = self::NIL)
    {
        $this->value = $value;
    }
}
