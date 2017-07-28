<?php

namespace Daikon\Tests\Entity\ValueObject\ValueObject;

use Daikon\Entity\ValueObject\Nil;
use Daikon\Entity\ValueObject\Text;
use Daikon\Tests\Entity\TestCase;

final class NilTest extends TestCase
{
    /**
     * @var Nil
     */
    private $nil;

    public function testToNative(): void
    {
        $this->assertNull($this->nil->toNative());
    }

    public function testEquals(): void
    {
        $this->assertTrue($this->nil->equals(Nil::fromNative(null)));
        $this->assertFalse($this->nil->equals(Text::fromNative(null)));
    }

    public function testToString(): void
    {
        $this->assertEquals('null', (string)$this->nil);
    }

    protected function setUp(): void
    {
        $this->nil = Nil::fromNative(null);
    }
}
