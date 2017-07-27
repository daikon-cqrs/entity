<?php

namespace Daikon\Entity\Entity;

use Daikon\Entity\ValueObject\ValueObjectInterface;

abstract class NestedEntity extends Entity implements ValueObjectInterface
{
    public function equals(ValueObjectInterface $otherValue): bool
    {
        if (!$otherValue instanceof self) {
            return false;
        }
        foreach ($this->getValueObjectMap() as $attrName => $value) {
            if (!$value->equals($otherValue->get($attrName))) {
                return false;
            }
        }
        return true;
    }

    public function __toString(): string
    {
        return sprintf("%s:%s", $this->getEntityType()->getName(), $this->getIdentity());
    }
}
