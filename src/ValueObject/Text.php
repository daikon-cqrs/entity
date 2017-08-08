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

final class Text implements ValueObjectInterface
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string|null $nativeValue
     * @return Text
     */
    public static function fromNative($nativeValue): Text
    {
        Assertion::nullOrString($nativeValue, 'Trying to create Text VO from unsupported value type.');
        return is_null($nativeValue) ? new static : new static($nativeValue);
    }

    public function equals(ValueObjectInterface $value): bool
    {
        return $value instanceof static && $this->toNative() === $value->toNative();
    }

    public function toNative(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->toNative();
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    public function getLength(): int
    {
        return strlen($this->value);
    }

    private function __construct(string $value = '')
    {
        $this->value = $value;
    }
}
