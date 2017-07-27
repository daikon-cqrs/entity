<?php

namespace Daikon\Entity\Assert;

use Assert\Assertion as BaseAssertion;
use Daikon\Entity\EntityTypeInterface;
use Daikon\Entity\Error\AssertionFailed;

final class Assertion extends BaseAssertion
{
    const MISSING_PARAM = 1000;

    protected static $exceptionClass = AssertionFailed::class;

    public static function hasParam(
        $paramContainer,
        string $paramName,
        string $message = null,
        string $propertyPath = null
    ): bool {
        if (!$paramContainer->hasParam($paramName)) {
            throw static::createException($paramName, $message, static::MISSING_PARAM, $propertyPath);
        }
        return true;
    }

    public static function hasArrayParam(
        $paramContainer,
        string $paramName,
        string $message = null,
        string $propertyPath = null
    ): bool {
        self::hasParam($paramContainer, $paramName, $message, $propertyPath);
        self::isArray($paramContainer->getParam($paramName), $message);
        return true;
    }

    protected static function stringify($value): string
    {
        return parent::stringify($value);
    }
}
