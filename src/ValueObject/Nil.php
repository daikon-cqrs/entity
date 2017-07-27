<?php

namespace Daikon\Entity\ValueObject;

use Daikon\Entity\Assert\Assertion;

final class Nil implements ValueObjectInterface
{
    /**
     * @param null $nativeValue
     * @return self
     */
    public static function fromNative($nativeValue): self
    {
        Assertion::null($nativeValue);
        return new self;
    }

    /**
     * @return null
     */
    public function toNative()
    {
        return null;
    }

    public function equals(ValueObjectInterface $otherValue): bool
    {
        return $otherValue instanceof Nil;
    }

    public function __toString(): string
    {
        return "null";
    }
}
