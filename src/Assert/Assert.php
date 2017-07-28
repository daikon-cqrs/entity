<?php

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
