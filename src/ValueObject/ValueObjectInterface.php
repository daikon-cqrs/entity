<?php

namespace Daikon\Entity\ValueObject;

use Daikon\Interop\FromNativeInterface;
use Daikon\Interop\ToNativeInterface;

interface ValueObjectInterface extends FromNativeInterface, ToNativeInterface
{
    public function equals(ValueObjectInterface $otherValue): bool;

    public function __toString(): string;
}
