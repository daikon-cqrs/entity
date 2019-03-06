<?php

namespace Daikon\Tests\Entity\ValueObject;

use Daikon\Entity\ValueObject\Timestamp;
use Daikon\Tests\Entity\TestCase;

final class TimestampTest extends TestCase
{
    private const FIXED_TIMESTAMP_EUR = '2016-07-04T19:27:07.000000+02:00';

    private const FIXED_TIMESTAMP_UTC = '2016-07-04T17:27:07.000000+00:00';

    private const FIXED_LATE_TIMESTAMP_UTC = '2016-07-05T17:27:07.000000+00:00';

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
        $equalTs = Timestamp::createFromString('2016-07-04T17:27:07', 'Y-m-d\\TH:i:s');
        $this->assertTrue($this->timestamp->equals($equalTs));
        $differentTs = Timestamp::createFromString('2017-08-04T17:27:07', 'Y-m-d\\TH:i:s');
        $this->assertFalse($this->timestamp->equals($differentTs));
    }

    public function testToString()
    {
        $this->assertEquals(self::FIXED_TIMESTAMP_UTC, (string)$this->timestamp);
    }

    public function testIsNull()
    {
        $nullTs = Timestamp::fromNative(null);
        $this->assertTrue($nullTs->isNull());
    }

    public function testIsBefore()
    {
        $nullTs = Timestamp::fromNative(null);
        $earlyTs = Timestamp::createFromString(self::FIXED_TIMESTAMP_UTC);
        $lateTs = Timestamp::createFromString(self::FIXED_LATE_TIMESTAMP_UTC);
        $this->assertTrue($nullTs->isBefore($earlyTs));
        $this->assertFalse($earlyTs->isBefore($nullTs));
        $this->assertTrue($earlyTs->isBefore($lateTs));
        $this->assertFalse($lateTs->isBefore($earlyTs));
    }

    public function testIsAfter()
    {
        $nullTs = Timestamp::fromNative(null);
        $earlyTs = Timestamp::createFromString(self::FIXED_TIMESTAMP_UTC);
        $lateTs = Timestamp::createFromString(self::FIXED_LATE_TIMESTAMP_UTC);
        $this->assertFalse($nullTs->isAfter($earlyTs));
        $this->assertTrue($earlyTs->isAfter($nullTs));
        $this->assertFalse($earlyTs->isAfter($lateTs));
        $this->assertTrue($lateTs->isAfter($earlyTs));
    }

    protected function setUp(): void
    {
        $this->timestamp = Timestamp::fromNative(self::FIXED_TIMESTAMP_EUR);
    }
}
