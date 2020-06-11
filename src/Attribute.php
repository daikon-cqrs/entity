<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Entity;

use Daikon\Interop\Assert;
use Daikon\Interop\Assertion;
use Daikon\ValueObject\ValueObjectInterface;

final class Attribute implements AttributeInterface
{
    private string $name;

    private string $valueType;

    public static function define(string $name, string $valueType): self
    {
        Assert::that($valueType)
            ->classExists(sprintf('Unable to load value type "%s"', $valueType))
            ->implementsInterface(ValueObjectInterface::class, sprintf(
                "Given value type '%s' does not implement required interface '%s'.",
                $valueType,
                ValueObjectInterface::class
            ));

        return new self($name, $valueType);
    }

    /** @param mixed $value */
    public function makeValue($value = null): ValueObjectInterface
    {
        if ($value instanceof ValueObjectInterface) {
            Assertion::isInstanceOf($value, $this->valueType, sprintf(
                "Value '%s' must be instance of value type '%s'.",
                get_class($value),
                $this->valueType
            ));
            return $value;
        }

        return ($this->valueType.'::fromNative')($value);
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
