<?php

namespace Daikon\Entity\Entity;

use Daikon\Entity\ValueObject\ValueObjectInterface;

interface EntityRelationInterface
{
    public function getRelatedIdentity(): ValueObjectInterface;
}
