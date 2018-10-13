<?php
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Daikon\Entity\Entity;

use Daikon\Entity\Assert\Assertion;
use Daikon\Entity\Exception\ClassNotExists;
use Daikon\Entity\Exception\UnexpectedType;
use Daikon\Entity\ValueObject\ValueObjectInterface;

final class Attribute implements AttributeInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $valueImplementor;

    public static function define(string $name, string $valueImplementor): AttributeInterface
    {
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
        return new static($name, $valueImplementor);
    }

    /**
     * @param mixed $value
     * @param EntityInterface $parent
     *
     * @return ValueObjectInterface
     */
    public function makeValue($value = null, EntityInterface $parent = null): ValueObjectInterface
    {
        if ($value instanceof ValueObjectInterface) {
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

    private function __construct(string $name, string $valueImplementor)
    {
        $this->name = $name;
        $this->valueImplementor = $valueImplementor;
    }
}
