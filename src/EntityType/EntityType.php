<?php

namespace Daikon\Entity\EntityType;

use Daikon\Entity\EntityType\Path\TypePathParser;
use Daikon\Entity\Error\InvalidType;

abstract class EntityType implements EntityTypeInterface
{
    /**
     * @var AttributeInterface
     */
    private $parentAttribute;

    /**
     * @var AttributeMap
     */
    private $attributeMap;

    /**
     * @var TypePathParser
     */
    private $pathParser;

    public function __construct(array $attributes, AttributeInterface $parentAttribute = null)
    {
        $this->parentAttribute = $parentAttribute;
        $this->pathParser = TypePathParser::create();
        $this->attributeMap = new AttributeMap($attributes);
    }

    public function getRoot(): EntityTypeInterface
    {
        $root = $this;
        $nextParent = $this->getParent();
        while ($nextParent) {
            $root = $nextParent;
            $nextParent = $root->getParent();
        }
        return $root;
    }

    public function getParentAttribute(): ?AttributeInterface
    {
        return $this->parentAttribute;
    }

    public function getParent(): ?EntityTypeInterface
    {
        return $this->hasParent() ? $this->getParentAttribute()->getEntityType() : null;
    }

    public function hasParent(): bool
    {
        return !is_null($this->getParentAttribute());
    }

    public function isRoot(): bool
    {
        return !$this->hasParent();
    }

    public function hasAttribute(string $typePath): bool
    {
        if (mb_strpos($typePath, ".")) {
            return $this->evaluatePath($typePath) !== null;
        }
        return $this->attributeMap->has($typePath);
    }

    public function getAttribute(string $typePath): AttributeInterface
    {
        if (mb_strpos($typePath, ".")) {
            return $this->evaluatePath($typePath);
        }
        if (!$this->attributeMap->has($typePath)) {
            throw new InvalidType("Attribute '$typePath' does not exist");
        }
        return $this->attributeMap->get($typePath);
    }

    public function getAttributes(array $typePaths = []): AttributeMap
    {
        $attributes = [];
        foreach ($typePaths as $typePath) {
            $attributes[] = $this->getAttribute($typePath);
        }
        return empty($typePaths) ? $this->attributeMap : new AttributeMap($attributes);
    }

    private function evaluatePath(string $typePath): AttributeInterface
    {
        $attribute = null;
        $entityType = $this;
        foreach ($this->pathParser->parse($typePath) as $pathPart) {
            /* @var NestedEntityListAttribute $attribute */
            $attribute = $entityType->getAttribute($pathPart->getAttributeName());
            if ($pathPart->hasType()) {
                $entityType = $attribute->getValueType()->get($pathPart->getType());
            }
        }
        return $attribute;
    }
}
