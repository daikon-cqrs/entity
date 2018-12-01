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
    /** @var string */
    public const NATIVE_FORMAT = 'Y-m-d\TH:i:s.uP';

    /** @var DateTimeImmutable|null */
    private $value;

    public static function now(): Timestamp
    {
        return new self(new DateTimeImmutable);
    }

    public static function createFromString(string $date, string $format = self::NATIVE_FORMAT): Timestamp
    {
        Assertion::date($date, $format);
        if (!$timestamp = DateTimeImmutable::createFromFormat($format, $date)) {
            throw new \RuntimeException('Invalid date string given to ' . self::class);
        }
        return new self($timestamp);
    }

    /** @param string|null $value */
    public static function fromNative($value): Timestamp
    {
        Assertion::nullOrString($value, 'Trying to create Timestamp VO from unsupported value type.');
        return empty($value) ? new self : self::createFromString($value);
    }

    public function toNative(): ?string
    {
        return is_null($this->value) ? null : $this->value->format(self::NATIVE_FORMAT);
    }

    public function equals(ValueObjectInterface $value): bool
    {
        return $value instanceof self && $this->toNative() === $value->toNative();
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
