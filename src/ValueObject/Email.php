<?php

namespace Daikon\Entity\ValueObject;

use Daikon\Entity\Assert\Assertion;

final class Email implements ValueObjectInterface
{
    /**
     * @var string
     */
    private const NIL = "";

    /**
     * @var Text
     */
    private $localPart;

    /**
     * @var Text
     */
    private $domain;

    /**
     * @param string|null $nativeValue
     * @return self
     */
    public static function fromNative($nativeValue): self
    {
        Assertion::nullOrString($nativeValue);
        if (empty($nativeValue)) {
            return new self(Text::fromNative(self::NIL), Text::fromNative(self::NIL));
        }
        Assertion::email($nativeValue, "Trying to create email from invalid string.");
        $parts = explode("@", $nativeValue);
        return new self(Text::fromNative($parts[0]), Text::fromNative(trim($parts[1], "[]")));
    }

    public function toNative(): string
    {
        if ($this->localPart->isEmpty() && $this->domain->isEmpty()) {
            return self::NIL;
        }
        return $this->localPart->toNative()."@".$this->domain->toNative();
    }

    public function equals(ValueObjectInterface $otherValue): bool
    {
        return $otherValue instanceof self && $this->toNative() === $otherValue->toNative();
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
