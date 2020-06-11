<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Tests\Entity;

use Daikon\Entity\EntityDiff;
use Daikon\Interop\InvalidArgumentException;
use Daikon\Tests\Entity\Fixture\Article;
use Daikon\Tests\Entity\Fixture\Location;
use Daikon\Tests\Entity\Fixture\Paragraph;
use Daikon\ValueObject\Text;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
    private const FIXED_UUID = '941b4e51-e524-4e5d-8c17-1ef96585abc3';

    private const FIXTURE = [
        '@type' => Article::class,
        'id' => '525b4e51-e524-4e5d-8c17-1ef96585cbd3',
        'created' => '2017-04-02T23:42:05.000000+00:00',
        'title' => 'hello world!',
        'url' => 'http://www.example.com/',
        'feedbackMail' => 'info@example.com',
        'averageVoting' => 23.42,
        'workshopDate' => '2017-05-23',
        'workshopCancelled' => true,
        'workshopLocation' => [
            '@type' => Location::class,
            'id' => 42,
            'coords' => [ 'lat' => 52.5119, 'lon' => 13.3084 ]
        ],
        'paragraphs' => [[
            '@type' => Paragraph::class,
            'id' => 23,
            'kicker' => 'this is the kicker baby!',
            'content' => 'hell yeah!'
        ]]
    ];

    private Article $entity;

    public function testGet(): void
    {
        $this->assertEquals(self::FIXTURE['id'], $this->entity->getIdentity()->toNative());
        $this->assertEquals(self::FIXTURE['title'], $this->entity->getTitle()->toNative());
    }

    public function testHas(): void
    {
        $this->assertTrue($this->entity->has('id'));
        $this->assertTrue($this->entity->has('title'));
        $this->assertTrue($this->entity->has('paragraphs'));
        $article = $this->entity::fromNative(['id' => '941b4e51-e524-4e5d-8c17-1ef96585abc3']);
        $this->assertFalse($article->has('title'));
    }

    public function testWith(): void
    {
        $article = $this->entity->withValue('id', self::FIXED_UUID);
        $this->assertEquals(self::FIXTURE['id'], $this->entity->getId()->toNative());
        $this->assertEquals(self::FIXED_UUID, $article->getId()->toNative());
    }

    public function testDiff(): void
    {
        $article = Article::fromNative([
            'id' => self::FIXED_UUID,
            'title' => 'Hello world!',
            'url' => 'http://metallica.com',
        ]);
        $diffData = [
            'title' => 'This is different',
            'url' => 'http://tv.lol',
        ];
        $calculatedDiff = (new EntityDiff)($article->withValues($diffData), $article);
        $this->assertEquals($diffData, $calculatedDiff->toNative());
    }

    public function testIsSameAs(): void
    {
        $articleTwo = Article::fromNative(['id' => self::FIXTURE['id'], 'title' => 'Hello world!']);
        // considered same, due to identifier
        $this->assertTrue($this->entity->isSameAs($articleTwo));

        $this->expectException(InvalidArgumentException::class);
        /** @psalm-suppress InvalidArgument */
        $this->entity->isSameAs(Location::fromNative([]));
    }

    public function testGetPath(): void
    {
        /** @var Text $value */
        $value = $this->entity->get('paragraphs.0-kicker');
        $this->assertEquals(self::FIXTURE['paragraphs'][0]['kicker'], $value->toNative());
    }

    public function testToNative(): void
    {
        $this->assertEquals(self::FIXTURE, $this->entity->toNative());
    }

    public function testInvalidValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Article::fromNative(['id' => self::FIXED_UUID, 'title' => [123]]);
    }

    public function testInvalidHas(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $article = Article::fromNative(['id' => self::FIXED_UUID]);
        $article->has('foobar');
    }

    public function testInvalidPath(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $article = Article::fromNative(['id' => self::FIXED_UUID]);
        $article->get('foo.0');
    }

    protected function setUp(): void
    {
        $this->entity = Article::fromNative(self::FIXTURE);
    }
}
