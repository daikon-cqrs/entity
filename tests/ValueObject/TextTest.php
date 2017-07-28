<?php

namespace Daikon\Tests\Entity\ValueObject;

use Daikon\Entity\ValueObject\Text;
use Daikon\Tests\Entity\TestCase;

final class TextTest extends TestCase
{
    private const FIXED_TEXT = 'hello world!';

    /**
     * @var Text
     */
    private $text;

    public function testToNative(): void
    {
        $this->assertEquals(self::FIXED_TEXT, $this->text->toNative());
        $this->assertEquals('', Text::fromNative(null)->toNative());
    }

    public function testEquals(): void
    {
        $sameText = Text::fromNative(self::FIXED_TEXT);
        $this->assertTrue($this->text->equals($sameText));
        $differentText = Text::fromNative('hello universe!');
        $this->assertFalse($this->text->equals($differentText));
    }

    public function testIsEmpty(): void
    {
        $this->assertTrue(Text::fromNative('')->isEmpty());
        $this->assertTrue(Text::fromNative(null)->isEmpty());
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
