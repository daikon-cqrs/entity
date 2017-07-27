<?php

namespace Daikon\Entity\Entity;

use Daikon\Entity\EntityType\EntityTypeInterface;

interface TypedEntityInterface extends EntityInterface
{
    public const ENTITY_TYPE = "@type";

    public function getValueObjectMap(): ValueObjectMap;

    public function getEntityRoot(): TypedEntityInterface;

    public function getEntityParent(): ?TypedEntityInterface;

    public function getEntityType(): EntityTypeInterface;
}
