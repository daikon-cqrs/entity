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
use DateTimeImmutable;

final class Date implements ValueObjectInterface
{
    /**
     * @var string
     */
    public const NATIVE_FORMAT = 'Y-m-d';

    /**
     * @var DateTimeImmutable|null
     */
    private $value;

    public static function today(): Date
    {
        return new static(new DateTimeImmutable);
    }

    public static function createFromString(string $value, string $format = self::NATIVE_FORMAT): self
    {
        Assertion::date($value, $format);
        if (!$date = DateTimeImmutable::createFromFormat($format, $value)) {
            throw new \RuntimeException("Invalid date string given to " . self::class);
        }
        return new static($date);
    }

    /**
     * @param string|null $nativeValue
     * @return Date
     */
    public static function fromNative($nativeValue): Date
    {
        Assertion::nullOrString($nativeValue, 'Trying to create Date VO from unsupported value type.');
        return empty($nativeValue) ? new static : static::createFromString($nativeValue);
    }

    public function toNative(): ?string
    {
        return is_null($this->value) ? null : $this->value->format(static::NATIVE_FORMAT);
    }

    public function equals(ValueObjectInterface $value): bool
    {
        return $value instanceof static && $this->toNative() === $value->toNative();
    }

    public function __toString(): string
    {
        return $this->toNative() ?? '';
    }

    private function __construct(DateTimeImmutable $value = null)
    {
        $this->value = $value ? $value->setTime(0, 0, 0) : $value;
    }
}
