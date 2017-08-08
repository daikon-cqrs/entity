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
use Ramsey\Uuid\Uuid as RamseyUuid;

final class Uuid implements ValueObjectInterface
{
    /**
     * @var RamseyUuid|null
     */
    private $value;

    public static function generate(): Uuid
    {
        return new static(RamseyUuid::uuid4());
    }

    /**
     * @param string|null $nativeValue
     * @return Uuid
     */
    public static function fromNative($nativeValue): Uuid
    {
        Assertion::nullOrString($nativeValue, 'Trying to create Uuid VO from unsupported value type.');
        return empty($nativeValue) ? new static : new static(RamseyUuid::fromString($nativeValue));
    }

    public function equals(ValueObjectInterface $value): bool
    {
        return $value instanceof static && $this->toNative() === $value->toNative();
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
