<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Entity;

use Assert\Assert;
use Daikon\Interop\RuntimeException;
use Daikon\ValueObject\ValueObjectInterface;
use InvalidArgumentException;

final class Attribute implements AttributeInterface
{
    private string $name;

    private string $valueType;

    public static function define(string $name, string $valueType): self
    {
        if (!class_exists($valueType)) {
            throw new RuntimeException(sprintf('Unable to load VO class "%s"', $valueType));
        }

        if (!is_subclass_of($valueType, ValueObjectInterface::class)) {
            throw new InvalidArgumentException(sprintf(
                'Given VO class "%s" does not implement required interface: %s',
                $valueType,
                ValueObjectInterface::class
            ));
        }

        return new self($name, $valueType);
    }

    /** @param mixed $value */
    public function makeValue($value = null): ValueObjectInterface
    {
        if ($value instanceof ValueObjectInterface) {
            Assert::that($value)->isInstanceOf($this->valueType);
            return $value;
        }

        return call_user_func([$this->valueType, 'fromNative'], $value);
    }

    public function getValueType(): string
    {
        return $this->valueType;
    }

    public function getName(): string
    {
        return $this->name;
    }

    private function __construct(string $name, string $valueType)
    {
        $this->name = $name;
        $this->valueType = $valueType;
    }
}
