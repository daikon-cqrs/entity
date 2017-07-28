<?php

namespace Daikon\Entity\Assert;

use Assert\Assertion as BaseAssertion;
use Daikon\Entity\EntityTypeInterface;
use Daikon\Entity\Exception\AssertionFailed;

final class Assertion extends BaseAssertion
{
    protected static $exceptionClass = AssertionFailed::class;
}
