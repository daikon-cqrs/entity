<?php

namespace Daikon\Entity\Entity;

use Daikon\Entity\ValueObject\ValueObjectInterface;
use Daikon\Interop\FromNativeInterface;
use Daikon\Interop\ToNativeInterface;

interface EntityInterface extends FromNativeInterface, ToNativeInterface
{
    public function getIdentity(): ValueObjectInterface;

    public function isSameAs(EntityInterface $entity): bool;

    public function withValue(string $attributeName, $value): EntityInterface;

    public function withValues(array $values): EntityInterface;

    public function get(string $valuePath): ValueObjectInterface;

    public function has(string $attributeName): bool;
}
