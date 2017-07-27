<?php

namespace Daikon\Tests\Entity\ValueObject;

use Daikon\Entity\ValueObject\Timestamp;
use Daikon\Tests\Entity\TestCase;

final class TimestampTest extends TestCase
{
    private const FIXED_TIMESTAMP_EUR = "2016-07-04T19:27:07.000000+02:00";

    private const FIXED_TIMESTAMP_UTC = "2016-07-04T17:27:07.000000+00:00";

    /**
     * @var Timestamp $timestamp
     */
    private $timestamp;

    public function testToNative(): void
    {
        $this->assertEquals(self::FIXED_TIMESTAMP_UTC, $this->timestamp->toNative());
        $this->assertEquals(null, Timestamp::fromNative(null)->toNative());
    }

    public function testEquals(): void
    {
        $equalTs = Timestamp::createFromString("2016-07-04T17:27:07", "Y-m-d\\TH:i:s");
        $this->assertTrue($this->timestamp->equals($equalTs));
        $differentTs = Timestamp::createFromString("2017-08-04T17:27:07", "Y-m-d\\TH:i:s");
        $this->assertFalse($this->timestamp->equals($differentTs));
    }

    public function testToString()
    {
        $this->assertEquals(self::FIXED_TIMESTAMP_UTC, (string)$this->timestamp);
    }

    protected function setUp(): void
    {
        $this->timestamp = Timestamp::fromNative(self::FIXED_TIMESTAMP_EUR);
    }
}
