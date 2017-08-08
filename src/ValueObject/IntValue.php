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

final class IntValue implements ValueObjectInterface
{
    /**
     * @var int|null
     */
    private $value;

    /**
     * @param int|null $nativeValue
     * @return IntValue
     */
    public static function fromNative($nativeValue): IntValue
    {
        Assertion::nullOrInteger($nativeValue, 'Trying to create IntValue VO from unsupported value type.');
        return new static($nativeValue);
    }

    public function toNative(): ?int
    {
        return $this->value;
    }

    public function equals(ValueObjectInterface $value): bool
    {
        return $value instanceof static && $this->toNative() === $value->toNative();
    }

    public function __toString(): string
    {
        return is_null($this->value) ? 'null' : (string)$this->value;
    }

    private function __construct(?int $value)
    {
        $this->value = $value;
    }
}
