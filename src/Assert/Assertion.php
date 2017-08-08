<?php
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Daikon\Entity\Assert;

use Assert\Assertion as BaseAssertion;
use Daikon\Entity\Exception\AssertionFailed;

final class Assertion extends BaseAssertion
{
    protected static $exceptionClass = AssertionFailed::class;
}
