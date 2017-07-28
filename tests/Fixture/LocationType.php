<?php

namespace Daikon\Tests\Entity\Fixture;

use Daikon\Entity\Entity\EntityInterface;
use Daikon\Entity\EntityType\Attribute;
use Daikon\Entity\EntityType\AttributeInterface;
use Daikon\Entity\EntityType\EntityType;
use Daikon\Entity\ValueObject\GeoPoint;
use Daikon\Entity\ValueObject\Integer;
use Daikon\Entity\ValueObject\Text;

final class LocationType extends EntityType
{
    public static function getName(): string
    {
        return 'Location';
    }

    /**
     * @param AttributeInterface $parentAttribute
     */
    public function __construct(AttributeInterface $parentAttribute)
    {
        parent::__construct([
            Attribute::define('id', Integer::class, $this),
            Attribute::define('name', Text::class, $this),
            Attribute::define('street', Text::class, $this),
            Attribute::define('postal_code', Text::class, $this),
            Attribute::define('city', Text::class, $this),
            Attribute::define('country', Text::class, $this),
            Attribute::define('coords', GeoPoint::class, $this)
        ], $parentAttribute);
    }

    /**
     * @inheritDoc
     */
    public function makeEntity(array $entityState = [], EntityInterface $parent = null): EntityInterface
    {
        $entityState['@type'] = $this;
        $entityState['@parent'] = $parent;
        return Location::fromNative($entityState);
    }
}
