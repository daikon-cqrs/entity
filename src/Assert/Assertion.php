<?php

namespace Daikon\Entity\Assert;

use Assert\Assertion as BaseAssertion;
use Daikon\Entity\EntityTypeInterface;
use Daikon\Entity\Error\AssertionFailed;

final class Assertion extends BaseAssertion
{
    const MISSING_PARAM = 1000;

    protected static $exceptionClass = AssertionFailed::class;
}
