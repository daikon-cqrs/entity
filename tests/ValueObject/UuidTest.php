<?php

namespace Daikon\Tests\Entity\ValueObject;

use Daikon\Entity\ValueObject\Uuid;
use Daikon\Tests\Entity\TestCase;

final class UuidTest extends TestCase
{
    private const FIXED_UUID = "110ec58a-a0f2-4ac4-8393-c866d813b8d1";

    /**
     * @var Uuid $uuid
     */
    private $uuid;

    public function testToNative(): void
    {
        $this->assertEquals(null, Uuid::fromNative(null)->toNative());
        $this->assertEquals(self::FIXED_UUID, $this->uuid->toNative());
    }

    public function testEquals(): void
    {
        $this->assertTrue($this->uuid->equals(Uuid::fromNative(self::FIXED_UUID)));
        $this->assertFalse($this->uuid->equals(Uuid::generate()));
        $this->assertFalse($this->uuid->equals(Uuid::fromNative(null)));
    }

    public function testString(): void
    {
        $this->assertEquals(self::FIXED_UUID, (string)$this->uuid);
    }

    protected function setUp(): void
    {
        $this->uuid = Uuid::fromNative(self::FIXED_UUID);
    }
}
