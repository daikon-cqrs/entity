<?php
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Daikon\Entity\ValueObject;

use Daikon\Entity\Assert\Assertion;

final class BoolValue implements ValueObjectInterface
{
    /**
     * @var bool
     */
    private $value;

    /**
     * @param bool $nativeValue
     * @return BoolValue
     */
    public static function fromNative($nativeValue): BoolValue
    {
        Assertion::boolean($nativeValue, 'Trying to create BoolValue VO from unsupported value type.');
        return new static($nativeValue);
    }

    public function toNative(): bool
    {
        return $this->value;
    }

    public function equals(ValueObjectInterface $value): bool
    {
        return $value instanceof static && $this->toNative() === $value->toNative();
    }

    public function __toString(): string
    {
        return $this->value ? 'true' : 'false';
    }

    public function isTrue(): bool
    {
        return $this->value === true;
    }

    public function isFalse(): bool
    {
        return $this->value === false;
    }

    public function negate(): BoolValue
    {
        $clone = clone $this;
        $clone->value = !$this->value;
        /** @noinspection PhpStrictTypeCheckingInspection */
        return $clone;
    }

    private function __construct(bool $value)
    {
        $this->value = $value;
    }
}
