<?php
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Daikon\Entity;

use Assert\Assertion;
use Daikon\Entity\Path\ValuePathParser;
use Daikon\ValueObject\ValueObjectInterface;

trait EntityTrait
{
    /** @var ValueObjectMap */
    private $valueObjectMap;

    /** @param ValuePathParser */
    private $pathParser;

    /** @param array $state */
    public static function fromNative($state): EntityInterface
    {
        return new static($state);
    }

    public function toNative(): array
    {
        $entityState = $this->valueObjectMap->toNative();
        $entityState[self::TYPE_KEY] = static::class;
        return $entityState;
    }

    public function isSameAs(EntityInterface $entity): bool
    {
        Assertion::isInstanceOf($entity, static::class);
        return $this->getIdentity()->equals($entity->getIdentity());
    }

    /** @param mixed $value */
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

    public function has(string $attributeName): bool
    {
        if (!$this->getAttributeMap()->has($attributeName)) {
            throw new \InvalidArgumentException(sprintf(
                'Attribute "%s" is not known to the entity %s',
                $attributeName,
                static::class
            ));
        }
        return $this->valueObjectMap->has($attributeName);
    }

    public function get(string $valuePath): ?ValueObjectInterface
    {
        if (mb_strpos($valuePath, '.')) {
            return $this->evaluatePath($valuePath);
        }
        if (!$this->getAttributeMap()->has($valuePath)) {
            throw new \InvalidArgumentException(sprintf(
                'Attribute "%s" is unknown to entity "%s"',
                $valuePath,
                static::class
            ));
        }
        return $this->valueObjectMap->has($valuePath) ? $this->valueObjectMap->get($valuePath) : null;
    }

    /** @param self $comparator */
    public function equals($comparator): bool
    {
        if (!$comparator instanceof static) {
            return false;
        }
        return (new EntityDiff)($this, $comparator)->isEmpty();
    }

    public function __toString(): string
    {
        return sprintf('%s:%s', static::class, $this->getIdentity());
    }

    private function __construct(array $values = [])
    {
        $this->valueObjectMap = ValueObjectMap::forEntity($this, $values);
        $this->pathParser = ValuePathParser::create();
    }

    private function evaluatePath(string $valuePath): ?ValueObjectInterface
    {
        $value = null;
        $entity = $this;
        foreach ($this->pathParser->parse($valuePath) as $pathPart) {
            $value = $entity->get($pathPart->getAttributeName());
            if ($value && $pathPart->hasPosition()) {
                if (!$value instanceof EntityListInterface) {
                    throw new \InvalidArgumentException('Trying to traverse non-entity value');
                }
                $entity = $value->get($pathPart->getPosition());
                $value = $entity;
            }
        }
        return $value;
    }
}
