<?php

namespace Daikon\Entity\EntityType;

use Daikon\Entity\Assert\Assertion;
use Daikon\Entity\Entity\NestedEntity;
use Daikon\Entity\Entity\EntityInterface;
use Daikon\Entity\Exception\ClassNotExists;
use Daikon\Entity\Exception\UnexpectedType;
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

    public function makeValue($value = null, EntityInterface $parent = null): ValueObjectInterface
    {
        if ($value instanceof NestedEntity) {
            foreach ($this->getValueType() as $type) {
                if ($type === $value->getEntityType()) {
                    return $value;
                }
            }
            throw new UnexpectedType(sprintf('Given entity-type is not allowed for attribute "%s"', $this->getName()));
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
                throw new ClassNotExists('Unable to load given entity-type class: '.$typeFqcn);
            }
            return new $typeFqcn($this);
        }, $allowedTypeClasses));
    }

    private function makeEntity(array $entityValues, EntityInterface $parentEntity = null): NestedEntity
    {
        Assertion::keyExists($entityValues, EntityInterface::TYPE_KEY);
        $typePrefix = $entityValues[EntityInterface::TYPE_KEY];
        if (!$this->allowedTypes->has($typePrefix)) {
            throw new UnexpectedType(
                sprintf('Unknown type prefix "%s" given within nested-entity values.', $typePrefix)
            );
        }
        /* @var NestedEntity $entity */
        $entity = $this->allowedTypes->get($typePrefix)->makeEntity($entityValues, $parentEntity);
        return $entity;
    }
}
