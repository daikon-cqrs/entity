<?php

namespace Daikon\Tests\Entity\ValueObject;

use Daikon\Entity\ValueObject\Decimal;
use Daikon\Tests\Entity\TestCase;

final class DecimalTest extends TestCase
{
    private const FIXED_DEC = 2.3;

    /**
     * @var Decimal
     */
    private $decimal;

    public function testToNative(): void
    {
        $this->assertEquals(self::FIXED_DEC, $this->decimal->toNative());
        $this->assertNull(Decimal::fromNative(null)->toNative());
    }

    public function testEquals(): void
    {
        $sameNumber = Decimal::fromNative(self::FIXED_DEC);
        $this->assertTrue($this->decimal->equals($sameNumber));
        $differentNumber = Decimal::fromNative(4.2);
        $this->assertFalse($this->decimal->equals($differentNumber));
    }

    public function testToString(): void
    {
        $this->assertEquals((string)self::FIXED_DEC, (string)$this->decimal);
        $this->assertEquals("null", (string)Decimal::fromNative(null));
    }

    protected function setUp(): void
    {
        $this->decimal = Decimal::fromNative(self::FIXED_DEC);
    }
}
