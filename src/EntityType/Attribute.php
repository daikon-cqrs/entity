<?php

namespace Daikon\Entity\EntityType;

use Daikon\Entity\EntityType\Path\TypePath;
use Daikon\Entity\EntityType\Path\TypePathPart;
use Daikon\Entity\Entity\EntityInterface;
use Daikon\Entity\Error\InvalidType;
use Daikon\Entity\Error\MissingImplementation;
use Daikon\Entity\ValueObject\ValueObjectInterface;

class Attribute implements AttributeInterface
{
    use AttributeTrait;

    /**
     * @var string
     */
    private $valueImplementor;

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function makeValue($value = null, EntityInterface $parent = null): ValueObjectInterface
    {
        if (!is_null($value) && $value instanceof $this->valueImplementor) {
            return $value;
        } elseif (is_null($value)) {
            return $this->valueImplementor::makeEmpty();
        } else {
            return $this->valueImplementor::fromNative($value);
        }
    }

    /**
     * @return string VO fqcn
     */
    public function getValueType(): string
    {
        return $this->valueImplementor;
    }

    /**
     * @param string $name
     * @param EntityTypeInterface $entityType
     * @param string $valueImplementor
     */
    protected function __construct(string $name, EntityTypeInterface $entityType, string $valueImplementor)
    {
        $this->name = $name;
        $this->valueImplementor = $valueImplementor;
        $this->entityType = $entityType;
    }
}
