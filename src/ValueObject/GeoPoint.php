<?php

namespace Daikon\Entity\ValueObject;

use Daikon\Entity\Assert\Assertion;

final class GeoPoint implements ValueObjectInterface
{
    /**
     * @var float[]
     */
    public const NULL_ISLAND = [
        "lon" => 0.0,
        "lat" => 0.0
    ];

    /**
     * @var Decimal
     */
    private $lon;

    /**
     * @var Decimal
     */
    private $lat;

    /**
     * @param float[] $point
     * @return GeoPoint
     */
    public static function fromArray(array $point): self
    {
        Assertion::keyExists($point, "lon");
        Assertion::keyExists($point, "lat");
        return new self(Decimal::fromNative($point['lon']), Decimal::fromNative($point['lat']));
    }

    /**
     * @param null|float[] $nativeValue
     * @return GeoPoint
     */
    public static function fromNative($nativeValue): self
    {
        Assertion::nullOrIsArray($nativeValue);
        return is_array($nativeValue) ? self::fromArray($nativeValue) : self::fromArray(self::NULL_ISLAND);
    }

    /**
     * @return float[]
     */
    public function toNative(): array
    {
        return [ "lon" => $this->lon->toNative(), "lat" => $this->lat->toNative() ];
    }

    public function equals(ValueObjectInterface $otherValue): bool
    {
        return $otherValue instanceof self && $this->toNative() == $otherValue->toNative();
    }

    public function __toString(): string
    {
        return sprintf("lon: %s, lat: %s", $this->lon, $this->lat);
    }

    public function isNullIsland(): bool
    {
        return $this->toNative() == self::NULL_ISLAND;
    }

    public function getLon(): Decimal
    {
        return $this->lon;
    }

    public function getLat(): Decimal
    {
        return $this->lat;
    }

    private function __construct(Decimal $lon, Decimal $lat)
    {
        $this->lon = $lon;
        $this->lat = $lat;
    }
}
