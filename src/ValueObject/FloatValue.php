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

final class FloatValue implements ValueObjectInterface
{
    /**
     * @var float|null
     */
    private $value;

    /**
     * @param float|null $nativeValue
     * @return FloatValue
     */
    public static function fromNative($nativeValue): FloatValue
    {
        Assertion::nullOrFloat($nativeValue, 'Trying to create FloatValue VO from unsupported value type.');
        return new static($nativeValue);
    }

    public function toNative(): ?float
    {
        return $this->value;
    }

    public function equals(ValueObjectInterface $value): bool
    {
        return $value instanceof static && $this->toNative() === $value->toNative();
    }

    public function __toString(): string
    {
        return $this->value ? (string)$this->value : 'null';
    }

    private function __construct(?float $value)
    {
        $this->value = $value;
    }
}
