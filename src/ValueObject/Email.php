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

final class Email implements ValueObjectInterface
{
    /** @var string */
    private const EMPTY = '';

    /** @var Text */
    private $localPart;

    /** @var Text */
    private $domain;

    /** @param string|null $value */
    public static function fromNative($value): Email
    {
        Assertion::nullOrString($value, 'Trying to create Email VO from unsupported value type.');
        if (empty($value)) {
            return new self(Text::fromNative(self::EMPTY), Text::fromNative(self::EMPTY));
        }
        Assertion::email($value, 'Trying to create email from invalid string.');
        $parts = explode('@', $value);
        return new self(Text::fromNative($parts[0]), Text::fromNative(trim($parts[1], '[]')));
    }

    public function toNative(): string
    {
        if ($this->localPart->isEmpty() && $this->domain->isEmpty()) {
            return self::EMPTY;
        }
        return $this->localPart->toNative().'@'.$this->domain->toNative();
    }

    public function equals(ValueObjectInterface $value): bool
    {
        return $value instanceof self && $this->toNative() === $value->toNative();
    }

    public function __toString(): string
    {
        return $this->toNative();
    }

    public function getLocalPart(): Text
    {
        return $this->localPart;
    }

    public function getDomain(): Text
    {
        return $this->domain;
    }

    private function __construct(Text $localPart, Text $domain)
    {
        $this->localPart = $localPart;
        $this->domain = $domain;
    }
}
