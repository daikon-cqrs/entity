<?php

namespace Daikon\Tests\Entity\Entity;

use Daikon\Entity\Entity\ValueObjectMap;
use Daikon\Tests\Entity\Fixture\ArticleType;
use Daikon\Tests\Entity\TestCase;

final class ValueObjectMapTest extends TestCase
{
    private const FIXED_DATA = [
        "id" => "525b4e51-e524-4e5d-8c17-1ef96585cbd3",
        "title" => "hello world!"
    ];

    /**
     * @var ValueObjectMap $valueObjectMap
     */
    private $valueObjectMap;

    public function testCount(): void
    {
        $this->assertCount(2, $this->valueObjectMap);
    }

    protected function setUp(): void
    {
        $this->valueObjectMap = (new ArticleType)
            ->makeEntity(self::FIXED_DATA)
            ->getValueObjectMap();
    }
}
