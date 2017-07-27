<?php

namespace Daikon\Entity\ValueObject;

interface ValueObjectListInterface extends ValueObjectInterface, \Countable, \IteratorAggregate
{
    public function diff(ValueObjectListInterface $otherList): self;
}
