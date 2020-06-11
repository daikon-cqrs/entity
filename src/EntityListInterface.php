<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Entity;

use Daikon\ValueObject\ValueObjectListInterface;

interface EntityListInterface extends ValueObjectListInterface
{
    public function diff(EntityListInterface $list): self;
}
