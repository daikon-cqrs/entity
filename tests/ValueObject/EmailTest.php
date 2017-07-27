<?php

namespace Daikon\Tests\Entity\ValueObject;

use Daikon\Entity\ValueObject\Email;
use Daikon\Tests\Entity\TestCase;

final class EmailTest extends TestCase
{
    private const EMAIL = "peter.parker@example.com";

    /**
     * @var Email
     */
    private $email;

    public function testToNative(): void
    {
        $this->assertEquals(self::EMAIL, $this->email->toNative());
        $this->assertEquals("", Email::fromNative(null)->toNative());
    }

    public function testEquals(): void
    {
        $sameEmail = Email::fromNative(self::EMAIL);
        $this->assertTrue($this->email->equals($sameEmail));
        $differentEmail = Email::fromNative("clark.kent@example.com");
        $this->assertFalse($this->email->equals($differentEmail));
    }

    public function testToString(): void
    {
        $this->assertEquals(self::EMAIL, (string)$this->email);
    }

    public function testGetLocalPart(): void
    {
        $this->assertEquals("peter.parker", (string)$this->email->getLocalPart());
    }

    public function testGetDomain(): void
    {
        $this->assertEquals("example.com", (string)$this->email->getDomain());
    }

    protected function setUp(): void
    {
        $this->email = Email::fromNative(self::EMAIL);
    }
}
