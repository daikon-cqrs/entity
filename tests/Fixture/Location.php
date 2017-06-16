<?php

namespace Accordia\Tests\Entity\Fixture;

use Accordia\Entity\Entity\NestedEntity;
use Accordia\Entity\ValueObject\GeoPoint;
use Accordia\Entity\ValueObject\Integer;
use Accordia\Entity\ValueObject\Text;
use Accordia\Entity\ValueObject\ValueObjectInterface;

final class Location extends NestedEntity
{
    /**
     * @return ValueObjectInterface
     */
    public function getIdentity(): ValueObjectInterface
    {
        return $this->getId();
    }

    /**
     * @return Integer
     */
    public function getId(): Integer
    {
        return $this->get("id");
    }

    /**
     * @return Text
     */
    public function getName(): Text
    {
        return $this->get("name");
    }

    /**
     * @return Text
     */
    public function getStreet(): Text
    {
        return $this->get("street");
    }

    /**
     * @return Text
     */
    public function getPostalCode(): Text
    {
        return $this->get("postal_code");
    }

    public function getCity(): Text
    {
        return $this->get("city");
    }

    /**
     * @return Text
     */
    public function getCountry(): Text
    {
        return $this->get("country");
    }

    /**
     * @return GeoPoint
     */
    public function getCoords(): GeoPoint
    {
        return $this->get("coords");
    }
}
