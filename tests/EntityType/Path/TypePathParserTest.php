<?php

namespace Daikon\Tests\Entity\EntityType\Path;

use Daikon\Entity\EntityType\Path\TypePath;
use Daikon\Entity\EntityType\Path\TypePathParser;
use Daikon\Tests\Entity\TestCase;

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
     * @expectedException \Daikon\Entity\Exception\InvalidPath
     */
    public function testMissingType(): void
    {
        TypePathParser::create()->parse('paragraphs.Paragraph..');
    } // @codeCoverageIgnore


    /**
     * @expectedException \Daikon\Entity\Exception\InvalidPath
     */
    public function testInvalidPath(): void
    {
        TypePathParser::create()->parse('paragraphs~');
    } // @codeCoverageIgnore

    /**
     * @expectedException \Daikon\Entity\Exception\InvalidPath
     */
    public function testMissingAttribute(): void
    {
        TypePathParser::create()->parse('paragraphs.Paragraph');
    } // @codeCoverageIgnore

    /**
     * @codeCoverageIgnore
     * @return mixed[]
     */
    public function provideTypePathTestData(): array
    {
        return [
            [
                'pathExpression' => 'paragraphs',
                'expectedLength' => 1
            ],
            [
                'pathExpression' => 'paragraphs.Paragraph-title',
                'expectedLength' => 2
            ],
            [
                'pathExpression' => 'slideshows.TeaserSlideshow-teasers.GalleryTeaser-images',
                'expectedLength' => 3
            ]
        ];
    }
}
