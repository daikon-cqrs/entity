<?php

namespace Daikon\Tests\Entity\ValueObject;

use Daikon\Entity\ValueObject\IntValue;
use Daikon\Tests\Entity\TestCase;

final class IntegerTest extends TestCase
{
    private const FIXED_NUM = 23;

    /**
     * @var Integer
     */
    private $integer;

    public function testToNative(): void
    {
        $this->assertEquals(self::FIXED_NUM, $this->integer->toNative());
        $this->assertNull(IntValue::fromNative(null)->toNative());
    }

    public function testEquals(): void
    {
        $sameNumber = IntValue::fromNative(self::FIXED_NUM);
        $this->assertTrue($this->integer->equals($sameNumber));
        $differentNumber = IntValue::fromNative(42);
        $this->assertFalse($this->integer->equals($differentNumber));
    }

    public function testToString(): void
    {
        $this->assertEquals((string)self::FIXED_NUM, (string)$this->integer);
    }

    protected function setUp(): void
    {
        $this->integer = IntValue::fromNative(self::FIXED_NUM);
    }
}
