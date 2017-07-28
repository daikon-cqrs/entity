<?php

namespace Daikon\Entity\EntityType;

use Daikon\Entity\Entity\TypedEntityInterface;
use Daikon\Entity\ValueObject\ValueObjectInterface;

interface AttributeInterface
{
    public static function define(
        string $name,
        $valueType,
        EntityTypeInterface $entityType
    ): AttributeInterface;

    public function makeValue($value = null, TypedEntityInterface $parent = null): ValueObjectInterface;

    public function getName(): string;

    public function getEntityType(): EntityTypeInterface;

    public function getParent(): ?AttributeInterface;

    public function getValueType();
}
