<?php

namespace Daikon\Tests\Entity\ValueObject;

use Daikon\Entity\ValueObject\FloatValue;
use Daikon\Tests\Entity\TestCase;

final class DecimalTest extends TestCase
{
    private const FIXED_DEC = 2.3;

    /**
     * @var FloatValue
     */
    private $decimal;

    public function testToNative(): void
    {
        $this->assertEquals(self::FIXED_DEC, $this->decimal->toNative());
        $this->assertNull(FloatValue::fromNative(null)->toNative());
    }

    public function testEquals(): void
    {
        $sameNumber = FloatValue::fromNative(self::FIXED_DEC);
        $this->assertTrue($this->decimal->equals($sameNumber));
        $differentNumber = FloatValue::fromNative(4.2);
        $this->assertFalse($this->decimal->equals($differentNumber));
    }

    public function testToString(): void
    {
        $this->assertEquals((string)self::FIXED_DEC, (string)$this->decimal);
        $this->assertEquals('null', (string)FloatValue::fromNative(null));
    }

    protected function setUp(): void
    {
        $this->decimal = FloatValue::fromNative(self::FIXED_DEC);
    }
}
