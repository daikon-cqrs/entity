<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Entity;

use Daikon\Entity\Path\ValuePathParser;
use Daikon\Entity\Path\ValuePathPart;
use Daikon\Interop\Assertion;
use Daikon\ValueObject\ValueObjectInterface;
use Daikon\ValueObject\ValueObjectMap;

trait EntityTrait
{
    private ValueObjectMap $valueObjectMap;

    private ValuePathParser $pathParser;

    final protected function __construct(array $state = [])
    {
        $this->pathParser = ValuePathParser::create();

        $objects = [];
        foreach ($this->getAttributeMap() as $name => $attribute) {
            if (array_key_exists($name, $state)) {
                $objects[$name] = $attribute->makeValue($state[$name]);
            }
        }

        $this->valueObjectMap = new ValueObjectMap($objects);
    }

    /**
     * @param array|null $state
     * @return static
     */
    public static function fromNative($state): self
    {
        /** @psalm-suppress UnsafeInstantiation */
        return new static($state);
    }

    public function toNative(): array
    {
        $entityState = $this->valueObjectMap->toNative();
        $entityState[EntityInterface::TYPE_KEY] = static::class;
        return $entityState;
    }

    /** @param static $entity */
    public function isSameAs($entity): bool
    {
        Assertion::isInstanceOf($entity, static::class);
        return $this->getIdentity()->equals($entity->getIdentity());
    }

    public function has(string $name): bool
    {
        $has = $this->getAttributeMap()->has($name);
        Assertion::true($has, sprintf("Attribute '%s' is not known to the entity '%s'.", $name, static::class));
        return $this->valueObjectMap->has($name);
    }

    public function get(string $name, $default = null): ?ValueObjectInterface
    {
        if (mb_strpos($name, '.')) {
            return $this->evaluatePath($name);
        }

        $attribute = $this->getAttributeMap()->get($name, null);
        Assertion::notNull($attribute, sprintf("Attribute '%s' is not known to entity '%s'.", $name, static::class));
        /** @psalm-suppress PossiblyNullArgument */
        $attributeType = get_class($attribute);
        Assertion::nullOrIsInstanceOf($default, $attributeType, sprintf(
            "Default type for '$name' must be null or $attributeType, but got '%s'.",
            is_object($default) ? get_class($default) : @gettype($default)
        ));

        return $this->valueObjectMap->get($name, $default);
    }

    /** @return static */
    public function withValue(string $name, $value): self
    {
        $copy = clone $this;
        $copy->valueObjectMap = $copy->valueObjectMap->with($name, $this->makeValue($name, $value));
        return $copy;
    }

    /** @return static */
    public function withValues(iterable $values): self
    {
        $copy = clone $this;
        foreach ($values as $name => $value) {
            $copy->valueObjectMap = $copy->valueObjectMap->with($name, $this->makeValue($name, $value));
        }
        return $copy;
    }

    /** @param static $comparator */
    public function equals($comparator): bool
    {
        Assertion::isInstanceOf($comparator, static::class);
        return (new EntityDiff)($this, $comparator)->isEmpty();
    }

    public function __toString(): string
    {
        return (string)$this->getIdentity();
    }

    /** @param mixed $value */
    private function makeValue(string $name, $value): ValueObjectInterface
    {
        $attribute = $this->getAttributeMap()->get($name, null);
        Assertion::isInstanceOf(
            $attribute,
            AttributeInterface::class,
            sprintf("Attribute '%s' is unknown to entity '%s'.", $name, static::class)
        );
        /** @var AttributeInterface $attribute */
        return $attribute->makeValue($value);
    }

    private function evaluatePath(string $valuePath): ?ValueObjectInterface
    {
        $entity = $this;
        /** @var ValuePathPart $pathPart */
        foreach ($this->pathParser->parse($valuePath) as $pathPart) {
            $name = $pathPart->getAttributeName();
            $value = $entity ? $entity->get($name) : null;
            if ($value && $pathPart->hasPosition()) {
                Assertion::isInstanceOf(
                    $value,
                    EntityList::class,
                    sprintf("Trying to traverse a non-entity list '%s' on entity '%s'.", $name, static::class)
                );
                /** @var EntityList $value */
                $entity = $value->get($pathPart->getPosition());
                $value = $entity;
            }
        }
        return $value ?? null;
    }

    public function __get(string $attribute)
    {
        return $this->get($attribute);
    }

    public function __clone()
    {
        $this->pathParser = clone $this->pathParser;
        $this->valueObjectMap = clone $this->valueObjectMap;
    }
}
