<?php

namespace Daikon\Tests\Entity\EntityType;

use Daikon\Entity\Entity\NestedEntityList;
use Daikon\Entity\Entity\TypedEntityInterface;
use Daikon\Entity\EntityType\EntityTypeInterface;
use Daikon\Entity\EntityType\NestedEntityListAttribute;
use Daikon\Tests\Entity\Fixture\Location;
use Daikon\Tests\Entity\Fixture\LocationType;
use Daikon\Tests\Entity\TestCase;

final class NestedEntityListAttributeTest extends TestCase
{
    private const FIXED_DATA = [ [
        "@type" => "Location",
        "id" => 42,
        "name" => "my poi",
        "street" => "fleetstreet 23",
        "postal_code" => "1337",
        "city" => "codetown",
        "country" => "Utopia",
        "coords" => [ "lon" => 0.0, "lat" => 0.0 ]
    ] ];

    /**
     * @var NestedEntityListAttribute $attribute
     */
    private $attribute;

    public function testMakeValueFromNative(): void
    {
        $locations = $this->attribute->makeValue(self::FIXED_DATA);
        $this->assertEquals(self::FIXED_DATA, $locations->toNative());
    }

    public function testMakeValueFromObject(): void
    {
        $parent = $this->getMockBuilder(TypedEntityInterface::class)->getMock();
        $locationType = $this->attribute->getValueType()->get("Location");
        $locationState = self::FIXED_DATA[0];
        $locationState["@type"] = $locationType;
        $locationState["@parent"] = $parent;
        $locations = NestedEntityList::wrap([ Location::fromNative($locationState) ], $parent);
        $this->assertEquals(self::FIXED_DATA, $this->attribute->makeValue($locations)->toNative());
    }

    /**
     * @expectedException \Daikon\Entity\Error\MissingImplementation
     */
    public function testNonExistingTypeClass(): void
    {
        /* @var EntityTypeInterface $entityType */
        $entityType = $this->getMockBuilder(EntityTypeInterface::class)->getMock();
        NestedEntityListAttribute::define("foo", [ "\\Daikon\Entity\\FooBaR" ], $entityType);
    } // @codeCoverageIgnore

    /**
     * @expectedException \Daikon\Entity\Error\CorruptValues
     */
    public function testInvalidType(): void
    {
        $data = self::FIXED_DATA;
        $data[0]["@type"] = "foobar";
        $this->attribute->makeValue($data);
    } // @codeCoverageIgnore

    /**
     * @expectedException \Daikon\Entity\Error\AssertionFailed
     */
    public function testMissingType(): void
    {
        $data = self::FIXED_DATA;
        unset($data[0]["@type"]);
        $this->attribute->makeValue($data);
    } // @codeCoverageIgnore

    /**
     * @expectedException \Daikon\Entity\Error\AssertionFailed
     */
    public function testUnexpectedValue(): void
    {
        $this->attribute->makeValue(5);
    } // @codeCoverageIgnore

    protected function setUp(): void
    {
        /* @var EntityTypeInterface $entityType */
        $entityType = $this->getMockBuilder(EntityTypeInterface::class)->getMock();
        $this->attribute = NestedEntityListAttribute::define("locations", [ LocationType::class ], $entityType);
    }
}
