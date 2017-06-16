<?php

namespace Accordia\Entity\Assert;

use Assert\Assert as BaseAssert;
use Accordia\Entity\Error\LazyAssertionFailed;

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
