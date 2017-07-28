<?php

namespace Daikon\Tests\Entity\EntityType;

use Daikon\Entity\EntityType\Attribute;
use Daikon\Entity\EntityType\Path\TypePath;
use Daikon\Entity\ValueObject\Text;
use Daikon\Tests\Entity\Fixture\ArticleType;
use Daikon\Tests\Entity\TestCase;

class EntityTypeTest extends TestCase
{
    /**
     * @var EntityTypeInterface $entityType
     */
    private $entityType;

    public function testGetName(): void
    {
        $this->assertEquals('Article', $this->entityType->getName());
    }

    public function testToTypePath(): void
    {
        $kickerAttr = $this->entityType->getAttribute('paragraphs.Paragraph-kicker');
        $this->assertEquals('paragraphs.Paragraph-kicker', (string)TypePath::fromAttribute($kickerAttr));
    }

    public function testGetAttribute(): void
    {
        $this->assertInstanceOf(Attribute::class, $this->entityType->getAttribute('title'));
        $this->assertInstanceOf(Attribute::class, $this->entityType->getAttribute('id'));
    }

    public function testGetAttributes(): void
    {
        $this->assertCount(2, $this->entityType->getAttributes([ 'title', 'id' ]));
        $this->assertCount(10, $this->entityType->getAttributes());
    }

    public function testGetParent(): void
    {
        $paragraphKicker = $this->entityType->getAttribute('paragraphs.Paragraph-kicker');
        $paragraphType = $paragraphKicker->getEntityType();
        $this->assertEquals($this->entityType, $paragraphType->getRoot());
        $this->assertEquals($this->entityType, $paragraphType->getParent());
        $this->assertTrue($paragraphType->hasParent());
        $this->assertFalse($this->entityType->hasParent());
        $this->assertTrue($this->entityType->isRoot());
        $this->assertFalse($paragraphType->isRoot());
    }

    public function testHasAttribute(): void
    {
        $this->assertTrue($this->entityType->hasAttribute('title'));
        $this->assertTrue($this->entityType->hasAttribute('paragraphs.Paragraph-kicker'));
    }

    public function testMakeEntity(): void
    {
        /* @var Article $article */
        $article = $this->entityType->makeEntity([
            'title' => 'hello world!',
            'content' => 'this is some test content ...'
        ]);
        $this->assertInstanceOf(Text::class, $article->getTitle());
        $this->assertEquals('hello world!', $article->getTitle()->toNative());
    }

    /**
     * @expectedException \Daikon\Entity\Exception\UnknownAttribute
     */
    public function testGetAttributeWithNonExistingAttribute(): void
    {
        $this->entityType->getAttribute('foobar');
    } // @codeCoverageIgnore

    protected function setUp(): void
    {
        $this->entityType = new ArticleType;
    }
}
