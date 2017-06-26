<?php

namespace Daikon\Tests\Entity\EntityType;

use Daikon\Entity\EntityType\Attribute;
use Daikon\Entity\EntityType\AttributeMap;
use Daikon\Entity\EntityType\EntityTypeInterface;
use Daikon\Tests\Entity\TestCase;
use Daikon\Entity\ValueObject\GeoPoint;
use Daikon\Entity\ValueObject\Integer;
use Daikon\Entity\ValueObject\Text;

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
