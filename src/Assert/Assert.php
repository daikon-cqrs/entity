<?php
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Daikon\Entity\Assert;

use Assert\Assert as BaseAssert;
use Daikon\Entity\Exception\LazyAssertionFailed;

abstract class Assert extends BaseAssert
{
    /**
     * @var string
     */
    protected static $lazyAssertionExceptionClass = LazyAssertionFailed::class;

    /**
     * @var string
     */
    protected static $assertionClass = Assertion::class;
}
