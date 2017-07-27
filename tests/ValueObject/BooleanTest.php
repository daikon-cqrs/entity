<?php

namespace Daikon\Tests\Entity\ValueObject;

use Daikon\Entity\ValueObject\Boolean;
use Daikon\Tests\Entity\TestCase;

final class BooleanTest extends TestCase
{
    public function testToNative(): void
    {
        $this->assertTrue(Boolean::fromNative(true)->toNative());
        $this->assertFalse(Boolean::fromNative(false)->toNative());
    }

    public function testEquals(): void
    {
        $bool = Boolean::fromNative(true);
        $this->assertTrue($bool->equals(Boolean::fromNative(true)));
        $this->assertFalse($bool->equals(Boolean::fromNative(false)));
    }

    public function testIsTrue(): void
    {
        $this->assertTrue(Boolean::fromNative(true)->isTrue());
    }

    public function testIsFalse(): void
    {
        $this->assertTrue(Boolean::fromNative(false)->isFalse());
    }

    public function testNegate(): void
    {
        $this->assertTrue(Boolean::fromNative(false)->negate()->toNative());
    }

    public function testToString(): void
    {
        $this->assertEquals("true", (string)Boolean::fromNative(true));
        $this->assertEquals("false", (string)Boolean::fromNative(false));
    }
}
