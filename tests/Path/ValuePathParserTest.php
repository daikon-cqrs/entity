<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Tests\Entity\Entity\Path;

use Daikon\Entity\Path\ValuePath;
use Daikon\Entity\Path\ValuePathParser;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ValuePathParserTest extends TestCase
{
    /**
     * @dataProvider provideValuePathTestData
     * @param string $pathExpression
     * @param int $expectedLength
     */
    public function testTypePath(string $pathExpression, int $expectedLength): void
    {
        $typePath = ValuePathParser::create()->parse($pathExpression);

        $this->assertCount($expectedLength, $typePath);
        $this->assertEquals($pathExpression, $typePath->__toString());
    }

    public function testInvalidPath(): void
    {
        $this->expectException(InvalidArgumentException::class);
        ValuePathParser::create()->parse('2-teasers');
    } // @codeCoverageIgnore

    /**
     * @codeCoverageIgnore
     * @return mixed[]
     */
    public function provideValuePathTestData(): array
    {
        return [
            [
                'pathExpression' => 'paragraphs',
                'expectedLength' => 1
            ],
            [
                'pathExpression' => 'paragraphs.1-title',
                'expectedLength' => 2
            ],
            [
                'pathExpression' => 'slideshows.2-teasers.3-images',
                'expectedLength' => 3
            ]
        ];
    }
}
