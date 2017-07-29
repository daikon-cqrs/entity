<?php

namespace Daikon\Entity\Entity;

use Daikon\Entity\ValueObject\ValueObjectInterface;

abstract class NestedEntity extends Entity implements ValueObjectInterface
{
    public function equals(ValueObjectInterface $entity): bool
    {
        if (!$entity instanceof static) {
            return false;
        }
        return (new EntityDiff)($this, $entity)->isEmpty();
    }

    public function __toString(): string
    {
        return sprintf('%s:%s', $this->getEntityType()->getName(), $this->getIdentity());
    }
}
