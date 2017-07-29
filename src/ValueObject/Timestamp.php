<?php

namespace Daikon\Entity\ValueObject;

use Daikon\Entity\Assert\Assertion;
use DateTimeImmutable;
use DateTimeZone;

final class Timestamp implements ValueObjectInterface
{
    /**
     * @var string
     */
    public const NATIVE_FORMAT = 'Y-m-d\TH:i:s.uP';

    /**
     * @var null
     */
    private const NIL = null;

    /**
     * @var DateTimeImmutable|null
     */
    private $value;

    public static function now(): Timestamp
    {
        return new Timestamp(new DateTimeImmutable);
    }

    public static function createFromString(string $date, string $format = self::NATIVE_FORMAT): Timestamp
    {
        Assertion::date($date, $format);
        return new Timestamp(DateTimeImmutable::createFromFormat($format, $date));
    }

    /**
     * @param string|null $nativeValue
     * @return Timestamp
     */
    public static function fromNative($nativeValue): Timestamp
    {
        Assertion::nullOrString($nativeValue, 'Trying to create Timestamp VO from unsupported value type.');
        return empty($nativeValue) ? new Timestamp : self::createFromString($nativeValue);
    }

    public function toNative(): ?string
    {
        return $this->value === self::NIL ? self::NIL : $this->value->format(self::NATIVE_FORMAT);
    }

    public function equals(ValueObjectInterface $value): bool
    {
        return $value instanceof self && $this->toNative() === $value->toNative();
    }

    public function __toString(): string
    {
        return $this->value ? $this->toNative() : 'null';
    }

    private function __construct(DateTimeImmutable $value = null)
    {
        $this->value = $value ? $value->setTimezone(new DateTimeZone('UTC')) : $value;
    }
}
