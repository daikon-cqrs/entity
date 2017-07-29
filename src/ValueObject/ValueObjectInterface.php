<?php

namespace Daikon\Entity\ValueObject;

use Daikon\Interop\FromNativeInterface;
use Daikon\Interop\ToNativeInterface;

interface ValueObjectInterface extends FromNativeInterface, ToNativeInterface
{
    public function equals(ValueObjectInterface $value): bool;

    public function __toString(): string;
}
