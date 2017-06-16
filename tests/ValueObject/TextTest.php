<?php

namespace Accordia\Tests\Entity\ValueObject;

use Accordia\Tests\Entity\TestCase;
use Accordia\Entity\ValueObject\Text;

final class TextTest extends TestCase
{
    private const FIXED_TEXT = "hello world!";

    /**
     * @var Text
     */
    private $text;

    public function testToNative(): void
    {
        $this->assertEquals(self::FIXED_TEXT, $this->text->toNative());
        $this->assertEquals("", Text::makeEmpty()->toNative());
    }

    public function testEquals(): void
    {
        $sameText = Text::fromNative(self::FIXED_TEXT);
        $this->assertTrue($this->text->equals($sameText));
        $differentText = Text::fromNative("hello universe!");
        $this->assertFalse($this->text->equals($differentText));
    }

    public function testIsEmpty(): void
    {
        $this->assertTrue(Text::makeEmpty()->isEmpty());
        $this->assertFalse($this->text->isEmpty());
    }

    public function testToString(): void
    {
        $this->assertEquals(self::FIXED_TEXT, (string)$this->text);
    }

    public function testGetLength()
    {
        $this->assertEquals(12, $this->text->getLength());
    }

    protected function setUp(): void
    {
        $this->text = Text::fromNative(self::FIXED_TEXT);
    }
}
