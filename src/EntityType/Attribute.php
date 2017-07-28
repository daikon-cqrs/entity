<?php

namespace Daikon\Entity\EntityType;

use Daikon\Entity\Assert\Assertion;
use Daikon\Entity\Entity\TypedEntityInterface;
use Daikon\Entity\Error\InvalidType;
use Daikon\Entity\Error\MissingImplementation;
use Daikon\Entity\ValueObject\ValueObjectInterface;

class Attribute implements AttributeInterface
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
     * @var string
     */
    private $valueImplementor;

    public static function define(
        string $name,
        $valueImplementor,
        EntityTypeInterface $entityType
    ): AttributeInterface {
        if (!class_exists($valueImplementor)) {
            throw new MissingImplementation("Unable to load VO class $valueImplementor");
        }
        if (!is_subclass_of($valueImplementor, ValueObjectInterface::class)) {
            throw new InvalidType("Given VO class $valueImplementor does not implement ".ValueObjectInterface::class);
        }
        return new static($name, $entityType, $valueImplementor);
    }

    public function makeValue($value = null, TypedEntityInterface $parent = null): ValueObjectInterface
    {
        if (is_object($value)) {
            Assertion::isInstanceOf($value, $this->valueImplementor);
            return $value;
        }
        return call_user_func([$this->valueImplementor, 'fromNative'], $value);
    }

    public function getValueType(): string
    {
        return $this->valueImplementor;
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

    protected function __construct(string $name, EntityTypeInterface $entityType, string $valueImplementor)
    {
        $this->name = $name;
        $this->valueImplementor = $valueImplementor;
        $this->entityType = $entityType;
    }
}
