<?php

namespace Accordia\Tests\Entity\EntityType\Path;

use Accordia\Tests\Entity\TestCase;
use Accordia\Entity\EntityType\Path\TypePath;
use Accordia\Entity\EntityType\Path\TypePathParser;

class TypePathParserTest extends TestCase
{
    /**
     * @dataProvider provideTypePathTestData
     * @param string $pathExpression
     * @param int $expectedLength
     */
    public function testTypePath(string $pathExpression, int $expectedLength): void
    {
        $typePath = TypePathParser::create()->parse($pathExpression);
        $this->assertInstanceOf(TypePath::class, $typePath);
        $this->assertCount($expectedLength, $typePath);
        $this->assertEquals($pathExpression, $typePath->__toString());
    }

    /**
     * @expectedException \Accordia\Entity\Error\InvalidTypePath
     */
    public function testMissingType(): void
    {
        TypePathParser::create()->parse("paragraphs.paragraph..");
    } // @codeCoverageIgnore


    /**
     * @expectedException \Accordia\Entity\Error\InvalidTypePath
     */
    public function testInvalidPath(): void
    {
        TypePathParser::create()->parse("paragraphs~");
    } // @codeCoverageIgnore

    /**
     * @expectedException \Accordia\Entity\Error\InvalidTypePath
     */
    public function testMissingAttribute(): void
    {
        TypePathParser::create()->parse("paragraphs.paragraph");
    } // @codeCoverageIgnore

    /**
     * @codeCoverageIgnore
     * @return mixed[]
     */
    public function provideTypePathTestData(): array
    {
        return [
            [
                "pathExpression" => "paragraphs",
                "expectedLength" => 1
            ],
            [
                "pathExpression" => "paragraphs.paragraph-title",
                "expectedLength" => 2
            ],
            [
                "pathExpression" => "slideshows.teaser_slideshow-teasers.gallery_teaser-images",
                "expectedLength" => 3
            ]
        ];
    }
}
