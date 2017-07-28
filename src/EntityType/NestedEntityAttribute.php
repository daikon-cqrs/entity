<?php

namespace Daikon\Entity\EntityType;

use Daikon\Entity\Assert\Assertion;
use Daikon\Entity\Entity\NestedEntity;
use Daikon\Entity\Entity\TypedEntityInterface;
use Daikon\Entity\Error\CorruptValues;
use Daikon\Entity\Error\MissingImplementation;
use Daikon\Entity\Error\UnexpectedValue;
use Daikon\Entity\ValueObject\Nil;
use Daikon\Entity\ValueObject\ValueObjectInterface;

class NestedEntityAttribute implements AttributeInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var EntityTypeInterface
     */
    private $entityType;

    /**
     * @var EntityTypeMap
     */
    private $allowedTypes;

    public static function define(
        string $name,
        $entityTypeClasses,
        EntityTypeInterface $entityType
    ): AttributeInterface {
        Assertion::isArray($entityTypeClasses);
        return new static($name, $entityType, $entityTypeClasses);
    }

    public function getValueType(): EntityTypeMap
    {
        return $this->allowedTypes;
    }

    public function makeValue($value = null, TypedEntityInterface $parent = null): ValueObjectInterface
    {
        if ($value instanceof NestedEntity) {
            foreach ($this->getValueType() as $type) {
                if ($type === $value->getEntityType()) {
                    return $value;
                }
            }
            throw new UnexpectedValue("Given entity-type is not allowed for attribute ".$this->getName());
        }
        Assertion::nullOrisArray($value);
        return is_array($value) ? $this->makeEntity($value, $parent) : Nil::fromNative($value);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEntityType(): EntityTypeInterface
    {
        return $this->entityType;
    }

    public function getParent(): ?AttributeInterface
    {
        return $this->getEntityType()->getParentAttribute();
    }

    protected function __construct(string $name, EntityTypeInterface $entityType, array $allowedTypeClasses)
    {
        $this->name = $name;
        $this->entityType = $entityType;
        $this->allowedTypes = new EntityTypeMap(array_map(function (string $typeFqcn) {
            if (!class_exists($typeFqcn)) {
                throw new MissingImplementation("Unable to load given entity-type class: '$typeFqcn'");
            }
            return new $typeFqcn($this);
        }, $allowedTypeClasses));
    }

    private function makeEntity(array $entityValues, TypedEntityInterface $parentEntity = null): NestedEntity
    {
        Assertion::keyExists($entityValues, TypedEntityInterface::ENTITY_TYPE);
        $typePrefix = $entityValues[TypedEntityInterface::ENTITY_TYPE];
        if (!$this->allowedTypes->has($typePrefix)) {
            throw new CorruptValues("Unknown type prefix given within nested-entity values.");
        }
        /* @var NestedEntity $entity */
        $entity = $this->allowedTypes->get($typePrefix)->makeEntity($entityValues, $parentEntity);
        return $entity;
    }
}
