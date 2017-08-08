<?php

namespace Daikon\Tests\Entity\Fixture;

use Daikon\Entity\Entity\EntityListInterface;
use Daikon\Entity\Entity\EntityListTrait;
use Ds\Vector;

final class ParagraphList implements EntityListInterface
{
    use EntityListTrait;

    private function __construct(array $paragraphs = [])
    {
        $this->compositeVector = new Vector((function (Paragraph ...$paragraphs): array {
             return $paragraphs;
        })(...$paragraphs));
    }
}
