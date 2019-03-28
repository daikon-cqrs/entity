<?php

namespace Daikon\Tests\Entity\Fixture;

use Daikon\Entity\Entity\Attribute;
use Daikon\Entity\Entity\AttributeMap;
use Daikon\Entity\Entity\EntityInterface;
use Daikon\Entity\Entity\EntityTrait;
use Daikon\Entity\ValueObject\GeoPoint;
use Daikon\Entity\ValueObject\IntValue;
use Daikon\Entity\ValueObject\Text;
use Daikon\Interop\ValueObjectInterface;

final class Location implements EntityInterface
{
    use EntityTrait;

    public static function getAttributeMap(): AttributeMap
    {
        return new AttributeMap([
            Attribute::define('id', IntValue::class),
            Attribute::define('name', Text::class),
            Attribute::define('street', Text::class),
            Attribute::define('postalCode', Text::class),
            Attribute::define('city', Text::class),
            Attribute::define('country', Text::class),
            Attribute::define('coords', GeoPoint::class)
        ]);
    }

    public function getIdentity(): ValueObjectInterface
    {
        return $this->getId();
    }

    public function getId(): IntValue
    {
        return $this->get('id');
    }

    public function getName(): Text
    {
        return $this->get('name');
    }

    public function getStreet(): Text
    {
        return $this->get('street');
    }

    public function getPostalCode(): Text
    {
        return $this->get('postalCode');
    }

    public function getCity(): Text
    {
        return $this->get('city');
    }

    public function getCountry(): Text
    {
        return $this->get('country');
    }

    public function getCoords(): GeoPoint
    {
        return $this->get('coords');
    }
}
