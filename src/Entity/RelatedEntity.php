<?php

namespace Daikon\Entity\Entity;

use Daikon\Entity\ValueObject\ValueObjectInterface;

abstract class RelatedEntity extends NestedEntity implements EntityRelationInterface
{
    public function getRelatedIdentity(): ValueObjectInterface
    {
        return $this->get('related_identity');
    }
}
