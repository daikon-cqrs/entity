<?php

namespace Daikon\Entity\EntityType;

interface EntityTypeRelationInterface
{
    public function getRelatedFqcn(): string;
}
