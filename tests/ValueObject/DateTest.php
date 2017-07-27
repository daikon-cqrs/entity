<?php

namespace Daikon\Tests\Entity\ValueObject;

use Daikon\Entity\ValueObject\Date;
use Daikon\Tests\Entity\TestCase;

final class DateTest extends TestCase
{
    private const DATE = "2016-07-04";

    /**
     * @var Date
     */
    private $date;

    public function testToNative(): void
    {
        $this->assertEquals(self::DATE, $this->date->toNative());
        $this->assertNull(Date::fromNative(null)->toNative());
    }

    public function testEquals(): void
    {
        $sameDate = Date::fromNative(self::DATE);
        $this->assertTrue($this->date->equals($sameDate));
        $sameDateOtherFormat = Date::createFromString("2016-07-04T19:27:07", "Y-m-d\\TH:i:s");
        $this->assertTrue($this->date->equals($sameDateOtherFormat));
        $differentDate = Date::fromNative("2017-08-10");
        $this->assertFalse($this->date->equals($differentDate));
    }

    public function testToString(): void
    {
        $this->assertEquals(self::DATE, (string)$this->date);
        $this->assertEquals("", (string)Date::fromNative(null));
    }

    protected function setUp(): void
    {
        $this->date = Date::fromNative(self::DATE);
    }
}
