<?php

namespace Daikon\Entity\EntityType;

use Daikon\Entity\Entity\TypedEntityInterface;

interface EntityTypeInterface
{
    public static function getName(): string;

    public function getRoot(): EntityTypeInterface;

    public function getParentAttribute(): ?AttributeInterface;

    public function getParent(): ?EntityTypeInterface;

    public function hasParent(): bool;

    public function isRoot(): bool;

    public function hasAttribute(string $typePath): bool;

    public function getAttribute(string $typePath): AttributeInterface;

    public function getAttributes(array $typePaths = []): AttributeMap;

    public function makeEntity(array $entityState = [], TypedEntityInterface $parent = null): TypedEntityInterface;
}
