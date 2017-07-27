<?php

namespace Daikon\Entity\Entity\Path;

final class ValuePathPart
{
    /**
     * @var string
     */
    private $attributeName;

    /**
     * @var int
     */
    private $position;

    public function __construct(string $attributeName, int $position = -1)
    {
        $this->attributeName = $attributeName;
        $this->position = $position;
    }

    public function getAttributeName(): string
    {
        return $this->attributeName;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function hasPosition(): bool
    {
        return $this->position >= 0;
    }

    public function __toString(): string
    {
        return $this->hasPosition()
            ? $this->getAttributeName().".".$this->getPosition()
            : $this->getAttributeName();
    }
}
