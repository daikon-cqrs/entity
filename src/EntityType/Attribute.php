<?php

namespace Daikon\Entity\EntityType;

use Daikon\Entity\Assert\Assertion;
use Daikon\Entity\Entity\EntityInterface;
use Daikon\Entity\Exception\UnexpectedType;
use Daikon\Entity\Exception\ClassNotExists;
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
            throw new ClassNotExists(sprintf('Unable to load VO class "%s"', $valueImplementor));
        }
        if (!is_subclass_of($valueImplementor, ValueObjectInterface::class)) {
            throw new UnexpectedType(sprintf(
                'Given VO class "%s" does not implement required interface: %s',
                $valueImplementor,
                ValueObjectInterface::class
            ));
        }
        return new static($name, $entityType, $valueImplementor);
    }

    public function makeValue($value = null, EntityInterface $parent = null): ValueObjectInterface
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
