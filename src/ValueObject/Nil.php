<?php

namespace Daikon\Entity\ValueObject;

use Daikon\Entity\Assert\Assertion;

final class Nil implements ValueObjectInterface
{
    /**
     * @param null $nativeValue
     * @return Nil
     */
    public static function fromNative($nativeValue): Nil
    {
        Assertion::null($nativeValue);
        return new Nil;
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
