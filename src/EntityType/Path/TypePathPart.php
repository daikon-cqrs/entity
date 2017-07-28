<?php

namespace Daikon\Entity\EntityType\Path;

final class TypePathPart
{
    /**
     * @var string
     */
    private $attributeName;

    /**
     * @var string
     */
    private $type;

    public function __construct(string $attributeName, string $type = '')
    {
        $this->attributeName = $attributeName;
        $this->type = $type;
    }

    public function getAttributeName(): string
    {
        return $this->attributeName;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function hasType(): bool
    {
        return !empty($this->type);
    }

    public function __toString(): string
    {
        return $this->hasType()
            ? $this->getAttributeName().'.'.$this->getType()
            : $this->getAttributeName();
    }
}
