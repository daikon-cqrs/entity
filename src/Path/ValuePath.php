<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Entity\Path;

use Daikon\DataStructure\TypedList;

final class ValuePath extends TypedList
{
    public function __construct(iterable $pathParts = [])
    {
        $this->init($pathParts, [ValuePathPart::class]);
    }

    public function __toString(): string
    {
        return $this->reduce(
            function (string $path, ValuePathPart $pathPart): string {
                return empty($path) ? (string)$pathPart : sprintf('%s-%s', $path, (string)$pathPart);
            },
            ''
        );
    }
}
