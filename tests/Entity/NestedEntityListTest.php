<?php

namespace Daikon\Tests\Entity\Entity;

use Daikon\Entity\Entity\NestedEntityList;
use Daikon\Entity\ValueObject\Text;
use Daikon\Tests\Entity\Fixture\ArticleType;
use Daikon\Tests\Entity\TestCase;

final class NestedEntityListTest extends TestCase
{
    private const FIXED_PARAGRAPH = [
        'id' => 42,
        'kicker' => 'hey ho',
        'content' => 'Foobar'
    ];

    /**
     * @var Paragraph
     */
    private $paragraph1;

    /**
     * @var Paragraph
     */
    private $paragraph2;

    /**
     * @var NestedEntityList
     */
    private $entityList;

    public function testToNative(): void
    {
        $expected = [ $this->paragraph1->toNative(), $this->paragraph2->toNative() ];
        $this->assertEquals($expected, $this->entityList->toNative());
    }

    public function testEquals(): void
    {
        $sameList = NestedEntityList::wrap([ $this->paragraph1, $this->paragraph2 ]);
        $this->assertTrue($this->entityList->equals($sameList));
        $differentList = NestedEntityList::wrap([ $this->paragraph1, $this->paragraph1 ]);
        $this->assertFalse($this->entityList->equals($differentList));
        $emptyList = NestedEntityList::makeEmpty();
        $this->assertFalse($this->entityList->equals($emptyList));
        $this->assertTrue($emptyList->equals(NestedEntityList::makeEmpty()));
    }

    public function testIsEmpty(): void
    {
        $this->assertTrue((NestedEntityList::makeEmpty())->isEmpty());
        $this->assertFalse($this->entityList->isEmpty());
    }

    public function testCount(): void
    {
        $this->assertCount(2, $this->entityList);
        $this->assertCount(0, NestedEntityList::makeEmpty());
    }

    public function testGetIterator()
    {
        $this->assertEquals(2, iterator_count($this->entityList));
    }

    public function testGetFirst(): void
    {
        $this->assertEquals($this->paragraph1, $this->entityList->getFirst());
    }

    public function testPush(): void
    {
        $entityList = (NestedEntityList::makeEmpty())
            ->push($this->entityList->getFirst())
            ->push($this->entityList->getFirst());
        $this->assertCount(2, $entityList);
    }

    public function testRemove(): void
    {
        $this->assertCount(1, $this->entityList->remove($this->entityList->get(1)));
    }

    public function testGetLast(): void
    {
        $this->assertEquals($this->paragraph2, $this->entityList->getLast());
    }

    public function testDiff(): void
    {
        $this->assertCount(1, $this->entityList->diff(NestedEntityList::wrap([ $this->paragraph1 ])));
    }

    public function testToString(): void
    {
        $this->assertEquals('Paragraph:42, Paragraph:5', (string)$this->entityList);
    }

    protected function setUp(): void
    {
        $articleType = new ArticleType;
        $article = $articleType->makeEntity();
        /* @var NestedEntityListAttribute $paragraphs */
        $paragraphs = $articleType->getAttribute('paragraphs');
        /* @var ParagraphType $paragraphType */
        $paragraphType = $paragraphs->getValueType()->get('Paragraph');
        $this->paragraph1 = $paragraphType->makeEntity(self::FIXED_PARAGRAPH, $article);
        $this->paragraph2 = $this->paragraph1->withValue('kicker', 'ho')->withValue('id', 5);
        $this->entityList = NestedEntityList::wrap([$this->paragraph1, $this->paragraph2]);
    }
}
