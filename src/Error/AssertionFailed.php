<?php

namespace Accordia\Entity\Error;

use Assert\InvalidArgumentException;

final class AssertionFailed extends InvalidArgumentException implements ErrorInterface
{

}
