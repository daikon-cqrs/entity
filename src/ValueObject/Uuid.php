<?php

namespace Daikon\Entity\ValueObject;

use Daikon\Entity\Assert\Assertion;
use Ramsey\Uuid\Uuid as RamseyUuid;

final class Uuid implements ValueObjectInterface
{
    /**
     * @var null
     */
    private const NIL = null;

    /**
     * @var RamseyUuid|null
     */
    private $value;

    public static function generate(): Uuid
    {
        return new Uuid(RamseyUuid::uuid4());
    }

    /**
     * @param string|null $nativeValue
     * @return Uuid
     */
    public static function fromNative($nativeValue): Uuid
    {
        Assertion::nullOrString($nativeValue, 'Trying to create Uuid VO from unsupported value type.');
        return empty($nativeValue) ? new Uuid : new Uuid(RamseyUuid::fromString($nativeValue));
    }

    public function equals(ValueObjectInterface $otherValue): bool
    {
        return $otherValue instanceof Uuid && $this->toNative() === $otherValue->toNative();
    }

    public function toNative(): ?string
    {
        return $this->value ? $this->value->toString() : $this->value;
    }

    public function __toString(): string
    {
        return $this->value ? $this->value->toString() : 'null';
    }

    private function __construct(RamseyUuid $value = null)
    {
        $this->value = $value;
    }
}
