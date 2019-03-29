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
use Daikon\Interop\ValueObjectInterface;
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
        if (!$dateTime = DateTimeImmutable::createFromFormat($format, $date)) {
            throw new \RuntimeException('Invalid date string given to ' . self::class);
        }
        return new self($dateTime);
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

    /** @param self $value */
    public function equals($value): bool
    {
        return $value instanceof self && $this->toNative() === $value->toNative();
    }

    public function isNull(): bool
    {
        return $this->value === null;
    }

    public function isBefore(Timestamp $comparand): bool
    {
        if ($this->isNull()) {
            return true;
        } elseif ($comparand->isNull()) {
            return false;
        } else {
            return $this->value < DateTimeImmutable::createFromFormat(self::NATIVE_FORMAT, (string)$comparand);
        }
    }

    public function isAfter(Timestamp $comparand): bool
    {
        if ($this->isNull()) {
            return false;
        } elseif ($comparand->isNull()) {
            return true;
        } else {
            return $this->value > DateTimeImmutable::createFromFormat(self::NATIVE_FORMAT, (string)$comparand);
        }
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
