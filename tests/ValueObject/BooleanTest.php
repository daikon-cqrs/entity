<?php

namespace Daikon\Tests\Entity\ValueObject;

use Daikon\Entity\ValueObject\BoolValue;
use Daikon\Tests\Entity\TestCase;

final class BooleanTest extends TestCase
{
    public function testToNative(): void
    {
        $this->assertTrue(BoolValue::fromNative(true)->toNative());
        $this->assertFalse(BoolValue::fromNative(false)->toNative());
    }

    public function testEquals(): void
    {
        $bool = BoolValue::fromNative(true);
        $this->assertTrue($bool->equals(BoolValue::fromNative(true)));
        $this->assertFalse($bool->equals(BoolValue::fromNative(false)));
    }

    public function testIsTrue(): void
    {
        $this->assertTrue(BoolValue::fromNative(true)->isTrue());
    }

    public function testIsFalse(): void
    {
        $this->assertTrue(BoolValue::fromNative(false)->isFalse());
    }

    public function testNegate(): void
    {
        $this->assertTrue(BoolValue::fromNative(false)->negate()->toNative());
    }

    public function testToString(): void
    {
        $this->assertEquals('true', (string)BoolValue::fromNative(true));
        $this->assertEquals('false', (string)BoolValue::fromNative(false));
    }
}
