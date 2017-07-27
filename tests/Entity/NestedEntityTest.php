<?php

namespace Daikon\Tests\Entity\Entity;

use Daikon\Entity\Entity\NestedEntity;
use Daikon\Entity\EntityTypeInterface;
use Daikon\Tests\Entity\Fixture\ArticleType;
use Daikon\Tests\Entity\TestCase;

final class NestedEntityTest extends TestCase
{
    private const FIXED_UUID = "941b4e51-e524-4e5d-8c17-1ef96585abc3";

    private const FIXED_DATA = [
        "id" => 42,
        "kicker" => "this is the kicker",
        "content" => "this is the content"
    ];

    /**
     * @var EntityTypeInterface $nestedEntityType
     */
    private $nestedEntityType;

    /**
     * @var NestedEntity $nestedEntity
     */
    private $nestedEntity;

    public function testEquals(): void
    {
        $equalEntity = $this->nestedEntityType->makeEntity(self::FIXED_DATA);
        $this->assertTrue($this->nestedEntity->equals($equalEntity));
        $unequalEntity = $equalEntity->withValue("kicker", "foobar");
        $this->assertFalse($this->nestedEntity->equals($unequalEntity));
    }

    public function testToString(): void
    {
        $this->assertEquals("Paragraph:42", (string)$this->nestedEntity);
    }

    protected function setUp(): void
    {
        $articleType = new ArticleType;
        $this->nestedEntityType = $articleType->getAttribute("paragraphs")
            ->getValueType()
            ->get("Paragraph");
        $this->nestedEntity = $this->nestedEntityType->makeEntity(self::FIXED_DATA);
    }
}
