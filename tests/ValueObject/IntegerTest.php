<?php

namespace Daikon\Tests\Entity\ValueObject;

use Daikon\Entity\ValueObject\Integer;
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
        $this->assertNull(Integer::fromNative(null)->toNative());
    }

    public function testEquals(): void
    {
        $sameNumber = Integer::fromNative(self::FIXED_NUM);
        $this->assertTrue($this->integer->equals($sameNumber));
        $differentNumber = Integer::fromNative(42);
        $this->assertFalse($this->integer->equals($differentNumber));
    }

    public function testToString(): void
    {
        $this->assertEquals((string)self::FIXED_NUM, (string)$this->integer);
    }

    protected function setUp()
    {
        $this->integer = Integer::fromNative(self::FIXED_NUM);
    }
}
