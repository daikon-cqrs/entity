<?php

namespace Daikon\Entity\EntityType;

use Ds\Vector;
use Daikon\Entity\Assert\Assert;
use Daikon\Entity\Assert\Assertion;
use Daikon\Entity\EntityType\EntityTypeMap;
use Daikon\Entity\Entity\EntityInterface;
use Daikon\Entity\Entity\NestedEntity;
use Daikon\Entity\Entity\TypedEntityInterface;
use Daikon\Entity\Error\CorruptValues;
use Daikon\Entity\Error\MissingImplementation;
use Daikon\Entity\Error\UnexpectedValue;
use Daikon\Entity\ValueObject\Nil;
use Daikon\Entity\ValueObject\ValueObjectInterface;

class NestedEntityAttribute implements AttributeInterface
{
    use AttributeTrait;

    /**
     * @var EntityTypeMap $allowedTypes
     */
    private $allowedTypes;

    /**
     * {@inheritdoc}
     */
    public static function define(
        string $name,
        $entityTypeClasses,
        EntityTypeInterface $entityType
    ): AttributeInterface {
        Assertion::isArray($entityTypeClasses);
        return new static($name, $entityType, $entityTypeClasses);
    }

    /**
     * @return EntityTypeMap
     */
    public function getValueType(): EntityTypeMap
    {
        return $this->allowedTypes;
    }

    /**
     * {@inheritdoc}
     */
    public function makeValue($value = null, EntityInterface $parent = null): ValueObjectInterface
    {
        if ($value instanceof NestedEntity) {
            foreach ($this->getValueType() as $type) {
                if ($type === $value->getEntityType()) {
                    return $value;
                }
            }
            throw new UnexpectedValue("Given entity-type is not allowed for attribute ".$this->getName());
        }
        Assert::that($value)->nullOr()->isArray();
        return is_null($value) ? Nil::makeEmpty() : $this->makeEntity($value, $parent);
    }

    /**
     * @param string $name
     * @param EntityTypeInterface $entityType
     * @param string[] $allowedTypeClasses
     */
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

    /**
     * @param array $entityValues
     * @param TypedEntityInterface $parentEntity
     * @return NestedEntity
     */
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
