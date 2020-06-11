<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Tests\Entity;

use Daikon\Entity\AttributeInterface;
use Daikon\Entity\AttributeMap;
use Daikon\Interop\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class AttributeMapTest extends TestCase
{
    public function testConstructWithSelf(): void
    {
        $attributeMock = $this->createMock(AttributeInterface::class);
        $attributeMock->expects($this->exactly(2))->method('getName')->willReturn('mock');

        $attributeMap = new AttributeMap([$attributeMock]);
        $newMap = new AttributeMap($attributeMap);
        $this->assertCount(1, $newMap);
        $this->assertFalse($attributeMap === $newMap);
    }

    public function testConstructWithDuplicateKey(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $attributeMock = $this->createMock(AttributeInterface::class);
        $attributeMock->expects($this->exactly(2))->method('getName')->willReturn('mock');
        new AttributeMap([$attributeMock, $attributeMock]);
    }

    public function testPush(): void
    {
        $emptyMap = new AttributeMap;
        /** @var AttributeInterface $attributeMock */
        $attributeMock = $this->createMock(AttributeInterface::class);
        $attributeMap = $emptyMap->with('mock', $attributeMock);
        $this->assertCount(1, $attributeMap);
        $this->assertEquals($attributeMock, $attributeMap->get('mock'));
        $this->assertTrue($emptyMap->isEmpty());
    }
}
