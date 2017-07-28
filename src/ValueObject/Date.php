<?php

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
     * @var null
     */
    private const NIL = null;

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
        return new Date(DateTimeImmutable::createFromFormat($format, $value));
    }

    /**
     * @param string|null $nativeValue
     * @return self
     */
    public static function fromNative($nativeValue): Date
    {
        Assertion::nullOrString($nativeValue, 'Trying to create Date VO from unsupported value type.');
        return empty($nativeValue) ? new self : self::createFromString($nativeValue);
    }

    public function toNative(): ?string
    {
        return $this->value === self::NIL ? self::NIL : $this->value->format(self::NATIVE_FORMAT);
    }

    public function equals(ValueObjectInterface $otherValue): bool
    {
        return $otherValue instanceof Date && $this->toNative() === $otherValue->toNative();
    }

    public function __toString(): string
    {
        return $this->toNative() ?? '';
    }

    private function __construct(DateTimeImmutable $value = self::NIL)
    {
        $this->value = $value ? $value->setTime(0, 0, 0) : $value;
    }
}
