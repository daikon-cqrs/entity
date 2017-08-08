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
use DateTimeZone;

final class Timestamp implements ValueObjectInterface
{
    /**
     * @var string
     */
    public const NATIVE_FORMAT = 'Y-m-d\TH:i:s.uP';

    /**
     * @var DateTimeImmutable|null
     */
    private $value;

    public static function now(): Timestamp
    {
        return new static(new DateTimeImmutable);
    }

    public static function createFromString(string $date, string $format = self::NATIVE_FORMAT): Timestamp
    {
        Assertion::date($date, $format);
        return new static(DateTimeImmutable::createFromFormat($format, $date));
    }

    /**
     * @param string|null $nativeValue
     * @return Timestamp
     */
    public static function fromNative($nativeValue): Timestamp
    {
        Assertion::nullOrString($nativeValue, 'Trying to create Timestamp VO from unsupported value type.');
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
        return $this->toNative() ?? 'null';
    }

    private function __construct(DateTimeImmutable $value = null)
    {
        $this->value = $value ? $value->setTimezone(new DateTimeZone('UTC')) : $value;
    }
}
