<?php

namespace Daikon\Entity\Entity;

use Daikon\Entity\EntityType\EntityTypeInterface;
use Daikon\Entity\ValueObject\ValueObjectInterface;
use Daikon\Interop\FromNativeInterface;
use Daikon\Interop\ToNativeInterface;

interface EntityInterface extends FromNativeInterface, ToNativeInterface
{
    /**
     * @var string
     */
    public const TYPE_KEY = '@type';

    /**
     * @var string
     */
    public const PARENT_KEY = '@parent';

    public function getIdentity(): ValueObjectInterface;

    public function isSameAs(EntityInterface $entity): bool;

    public function withValue(string $attributeName, $value): EntityInterface;

    public function withValues(array $values): EntityInterface;

    public function get(string $valuePath): ?ValueObjectInterface;

    public function has(string $attributeName): bool;

    public function getValueObjectMap(): ValueObjectMap;

    public function getEntityRoot(): EntityInterface;

    public function getEntityParent(): ?EntityInterface;

    public function getEntityType(): EntityTypeInterface;
}
