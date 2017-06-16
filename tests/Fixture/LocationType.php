<?php

namespace Accordia\Tests\Entity\Fixture;

use Accordia\Entity\EntityType\Attribute;
use Accordia\Entity\EntityType\AttributeInterface;
use Accordia\Entity\EntityType\EntityType;
use Accordia\Entity\Entity\TypedEntityInterface;
use Accordia\Entity\ValueObject\GeoPoint;
use Accordia\Entity\ValueObject\Integer;
use Accordia\Entity\ValueObject\Text;

final class LocationType extends EntityType
{
    /**
     * @param AttributeInterface $parentAttribute
     */
    public function __construct(AttributeInterface $parentAttribute)
    {
        parent::__construct("Location", [
            Attribute::define("id", Integer::class, $this),
            Attribute::define("name", Text::class, $this),
            Attribute::define("street", Text::class, $this),
            Attribute::define("postal_code", Text::class, $this),
            Attribute::define("city", Text::class, $this),
            Attribute::define("country", Text::class, $this),
            Attribute::define("coords", GeoPoint::class, $this)
        ], $parentAttribute);
    }

    /**
     * @inheritDoc
     */
    public function makeEntity(array $entityState = [], TypedEntityInterface $parent = null): TypedEntityInterface
    {
        $entityState["@type"] = $this;
        $entityState["@parent"] = $parent;
        return Location::fromArray($entityState);
    }
}
