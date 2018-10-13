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

final class GeoPoint implements ValueObjectInterface
{
    /**
     * @var float[]
     */
    public const NULL_ISLAND = [ 'lon' => 0.0, 'lat' => 0.0 ];

    /**
     * @var FloatValue
     */
    private $lon;

    /**
     * @var FloatValue
     */
    private $lat;

    /**
     * @param float[] $point
     * @return GeoPoint
     */
    public static function fromArray(array $point): GeoPoint
    {
        Assertion::keyExists($point, 'lon');
        Assertion::keyExists($point, 'lat');
        return new GeoPoint(FloatValue::fromNative($point['lon']), FloatValue::fromNative($point['lat']));
    }

    /**
     * @param float[]|null $nativeValue
     * @return GeoPoint
     */
    public static function fromNative($nativeValue): GeoPoint
    {
        Assertion::nullOrIsArray($nativeValue, 'Trying to create GeoPoint VO from unsupported value type.');
        return is_array($nativeValue) ? static::fromArray($nativeValue) : static::fromArray(static::NULL_ISLAND);
    }

    /**
     * @return array{lon:float|null, lat:float|null}
     */
    public function toNative(): array
    {
        return [ 'lon' => $this->lon->toNative(), 'lat' => $this->lat->toNative() ];
    }

    public function equals(ValueObjectInterface $value): bool
    {
        return $value instanceof static && $this->toNative() == $value->toNative();
    }

    public function __toString(): string
    {
        return sprintf('lon: %s, lat: %s', $this->lon, $this->lat);
    }

    public function isNullIsland(): bool
    {
        return $this->toNative() == static::NULL_ISLAND;
    }

    public function getLon(): FloatValue
    {
        return $this->lon;
    }

    public function getLat(): FloatValue
    {
        return $this->lat;
    }

    private function __construct(FloatValue $lon, FloatValue $lat)
    {
        $this->lon = $lon;
        $this->lat = $lat;
    }
}
