<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Tests\Entity\Fixture;

use Daikon\Entity\EntityListInterface;
use Daikon\Entity\EntityListTrait;

final class ParagraphList implements EntityListInterface
{
    use EntityListTrait;

    private function __construct(iterable $paragraphs = [])
    {
        $this->init($paragraphs, Paragraph::class);
    }
}
