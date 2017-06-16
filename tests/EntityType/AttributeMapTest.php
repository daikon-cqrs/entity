<?php

namespace Accordia\Tests\Entity\EntityType;

use Accordia\Entity\EntityType\Attribute;
use Accordia\Entity\EntityType\AttributeMap;
use Accordia\Entity\EntityType\EntityTypeInterface;
use Accordia\Tests\Entity\TestCase;
use Accordia\Entity\ValueObject\GeoPoint;
use Accordia\Entity\ValueObject\Integer;
use Accordia\Entity\ValueObject\Text;

final class AttributeMapTest extends TestCase
{
    /**
     * @var AttributeMap
     */
    private $attributeMap;

    public function testGet()
    {
        $this->assertInstanceOf(Attribute::class, $this->attributeMap->get("id"));
        $this->assertInstanceOf(Attribute::class, $this->attributeMap->get("name"));
        $this->assertInstanceOf(Attribute::class, $this->attributeMap->get("location"));
    }

    public function testByClassNames()
    {
        $attributeMap = $this->attributeMap->byClassNames([ Attribute::class ]);
        $this->assertCount(3, $attributeMap);
    }

    public function testHas()
    {
        $this->assertTrue($this->attributeMap->has("id"));
        $this->assertTrue($this->attributeMap->has("name"));
        $this->assertTrue($this->attributeMap->has("location"));
        $this->assertFalse($this->attributeMap->has("foobar"));
    }

    public function testCount()
    {
        $this->assertCount(3, $this->attributeMap);
    }

    protected function setUp()
    {
        /* @var EntityTypeInterface $entityType */
        $entityType = $this->getMockBuilder(EntityTypeInterface::class)->getMock();
        $this->attributeMap = new AttributeMap([
            Attribute::define("id", Integer::class, $entityType),
            Attribute::define("name", Text::class, $entityType),
            Attribute::define("location", GeoPoint::class, $entityType)
        ]);
    }
}
