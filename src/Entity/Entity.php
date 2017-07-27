<?php

namespace Daikon\Entity\Entity;

use Daikon\Entity\Assert\Assertion;
use Daikon\Entity\Entity\Path\ValuePathParser;
use Daikon\Entity\EntityType\EntityTypeInterface;
use Daikon\Entity\Error\UnknownAttribute;
use Daikon\Entity\ValueObject\Nil;
use Daikon\Entity\ValueObject\ValueObjectInterface;

abstract class Entity implements TypedEntityInterface
{
    /**
     * @var EntityTypeInterface
     */
    private $type;

    /**
     * @var EntityInterface
     */
    private $parent;

    /**
     * @var ValueObjectMap
     */
    private $valueObjectMap;

    /**
     * @param ValuePathParser
     */
    private $pathParser;

    public static function fromNative($nativeState): EntityInterface
    {
        $entityType = $nativeState["@type"];
        Assertion::isInstanceOf($entityType, EntityTypeInterface::class);
        $parent = null;
        if (isset($nativeState["@parent"])) {
            $parent = $nativeState["@parent"];
            Assertion::isInstanceOf($parent, TypedEntityInterface::class);
            unset($nativeState["@parent"]);
        }
        return new static($entityType, $nativeState, $parent);
    }

    public function toNative(): array
    {
        $entityState = $this->valueObjectMap->toArray();
        $entityState[self::ENTITY_TYPE] = $this->getEntityType()->getName();
        return $entityState;
    }

    public function isSameAs(EntityInterface $entity): bool
    {
        Assertion::isInstanceOf($entity, static::class);
        return $this->getIdentity()->equals($entity->getIdentity());
    }

    public function withValue(string $attributeName, $value): EntityInterface
    {
        $copy = clone $this;
        $copy->valueObjectMap = $this->valueObjectMap->withValue($attributeName, $value);
        return $copy;
    }

    public function withValues(array $values): EntityInterface
    {
        $copy = clone $this;
        $copy->valueObjectMap = $this->valueObjectMap->withValues($values);
        return $copy;
    }

    public function getValueObjectMap(): ValueObjectMap
    {
        return $this->valueObjectMap;
    }

    public function has(string $attributeName): bool
    {
        if (!$this->type->hasAttribute($attributeName)) {
            throw new UnknownAttribute("Attribute '$attributeName' is not known to the entity's value-map. ");
        }
        return $this->valueObjectMap->has($attributeName);
    }

    public function get(string $valuePath): ?ValueObjectInterface
    {
        if (mb_strpos($valuePath, ".")) {
            return $this->evaluatePath($valuePath);
        }
        if (!$this->type->hasAttribute($valuePath)) {
            throw new UnknownAttribute("Attribute '$valuePath' is unknown by type ".$this->getEntityType()->getName());
        }
        return $this->valueObjectMap->has($valuePath) ? $this->valueObjectMap->get($valuePath) : null;
    }

    public function getEntityRoot(): TypedEntityInterface
    {
        $tmpParent = $this->getEntityParent();
        $root = $tmpParent;
        while ($tmpParent) {
            $root = $tmpParent;
            $tmpParent = $tmpParent->getEntityParent();
        }
        return $root ?? $this;
    }

    public function getEntityParent(): ?TypedEntityInterface
    {
        return $this->parent;
    }

    public function getEntityType(): EntityTypeInterface
    {
        return $this->type;
    }

    protected function __construct(EntityTypeInterface $type, array $values = [], TypedEntityInterface $parent = null)
    {
        $this->type = $type;
        $this->parent = $parent;
        $this->valueObjectMap = ValueObjectMap::forEntity($this, $values);
        $this->pathParser = ValuePathParser::create();
    }

    private function evaluatePath($valuePath): ?ValueObjectInterface
    {
        $value = null;
        $entity = $this;
        foreach ($this->pathParser->parse($valuePath) as $pathPart) {
            /* @var TypedEntityInterface $value */
            $value = $entity->get($pathPart->getAttributeName());
            if ($pathPart->hasPosition()) {
                $entity = $value->get($pathPart->getPosition());
                $value = $entity;
            }
        }
        return $value;
    }
}
