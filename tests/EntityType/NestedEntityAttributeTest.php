<?php

namespace Daikon\Tests\Entity\EntityType;

use Daikon\Entity\EntityType\EntityTypeInterface;
use Daikon\Entity\EntityType\NestedEntityAttribute;
use Daikon\Entity\ValueObject\Nil;
use Daikon\Tests\Entity\Fixture\Location;
use Daikon\Tests\Entity\Fixture\LocationType;
use Daikon\Tests\Entity\TestCase;

final class NestedEntityAttributeTest extends TestCase
{
    private const FIXED_DATA = [
        "@type" => "Location",
        "id" => 42,
        "name" => "my poi",
        "street" => "fleetstreet 23",
        "postal_code" => "1337",
        "city" => "codetown",
        "country" => "Utopia",
        "coords" => [ "lon" => 0.0, "lat" => 0.0 ]
    ];

    /**
     * @var NestedEntityAttribute $attribute
     */
    private $attribute;

    public function testMakeValueFromNative(): void
    {
        $this->assertEquals(self::FIXED_DATA, $this->attribute->makeValue(self::FIXED_DATA)->toNative());
    }

    public function testMakeValueFromObject(): void
    {
        $locationType = $this->attribute->getValueType()->get("Location");
        $locationState = self::FIXED_DATA;
        $locationState["@type"] = $locationType;
        $location = Location::fromNative($locationState);
        $this->assertEquals(self::FIXED_DATA, $this->attribute->makeValue($location)->toNative());
    }

    public function testMakeEmptyValue(): void
    {
        $this->assertInstanceOf(Nil::class, $this->attribute->makeValue());
    }

    /**
     * @expectedException \Daikon\Entity\Error\AssertionFailed
     */
    public function testUnexpectedValue(): void
    {
        $this->attribute->makeValue("snafu!");
    } // @codeCoverageIgnore

    /**
     * @expectedException \Daikon\Entity\Error\MissingImplementation
     */
    public function testNonExistingTypeClass(): void
    {
        /* @var EntityTypeInterface $entityType */
        $entityType = $this->getMockBuilder(EntityTypeInterface::class)->getMock();
        NestedEntityAttribute::define("foo", [ "\\Daikon\Entity\\FooBaR" ], $entityType);
    } // @codeCoverageIgnore

    /**
     * @expectedException \Daikon\Entity\Error\CorruptValues
     */
    public function testInvalidType(): void
    {
        $data = self::FIXED_DATA;
        $data["@type"] = "foobar";
        $this->attribute->makeValue($data);
    } // @codeCoverageIgnore

    /**
     * @expectedException \Daikon\Entity\Error\AssertionFailed
     */
    public function testMissingType(): void
    {
        $data = self::FIXED_DATA;
        unset($data["@type"]);
        $this->attribute->makeValue($data);
    } // @codeCoverageIgnore

    protected function setUp(): void
    {
        /* @var EntityTypeInterface $entityType */
        $entityType = $this->getMockBuilder(EntityTypeInterface::class)->getMock();
        $this->attribute = NestedEntityAttribute::define("locations", [ LocationType::class ], $entityType);
    }
}
