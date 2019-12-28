<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Entity\Path;

use InvalidArgumentException;
use JMS\Parser\AbstractParser;
use JMS\Parser\SimpleLexer;

final class ValuePathParser extends AbstractParser
{
    private const T_ATTRIBUTE = 1;

    private const T_POSITION = 2;

    private const T_COMPONENT_SEP = 3;

    private const T_PART_SEP = 4;

    private const TOKEN_REGEX = <<<REGEX
/
    # type identifier which refers to an attribute
    ([a-zA-Z_]+)

    # value position
    |(\d+)

    # value-path-component separator. the two components of a value-path-part being attribute and position.
    |(\.)

    # value-path separator
    |(\-)
/x
REGEX;

    private const TOKEN_MAP = [
        0 => 'T_UNKNOWN',
        1 => 'T_ATTRIBUTE',
        2 => 'T_POSITION',
        3 => 'T_PART_SEP'
    ];

    public static function create(): ValuePathParser
    {
        $mapToken = function (string $token): array {
            switch ($token) {
                case '.':
                    return [ self::T_COMPONENT_SEP, $token ];
                case '-':
                    return [ self::T_PART_SEP, $token ];
                default:
                    return is_numeric($token)
                        ? [ self::T_POSITION, (int)$token ]
                        : [ self::T_ATTRIBUTE, $token ];
            }
        };
        $lexer = new SimpleLexer(self::TOKEN_REGEX, self::TOKEN_MAP, $mapToken);
        return new ValuePathParser($lexer);
    }

    /**
     * @param string $path
     * @param string $context
     */
    public function parse($path, $context = null): ValuePath
    {
        return parent::parse($path, $context);
    }

    public function parseInternal(): ValuePath
    {
        $valuePathParts = [];
        while ($valuePathPart = $this->consume()) {
            $valuePathParts[] = $valuePathPart;
        }
        return new ValuePath($valuePathParts);
    }

    private function consume(): ?ValuePathPart
    {
        $this->eatSeparator();
        $attribute = $this->parseAttribute();
        if (is_null($attribute)) {
            return null;
        }
        return new ValuePathPart($attribute, $this->parsePosition());
    }

    private function eatSeparator(): void
    {
        if ($this->lexer->isNext(self::T_PART_SEP)) {
            $this->match(self::T_PART_SEP);
        }
    }

    private function parseAttribute(): ?string
    {
        if (!$this->lexer->isNext(self::T_ATTRIBUTE)) {
            /** @psalm-suppress MissingPropertyType */
            if (!is_null($this->lexer->next)) {
                throw new InvalidArgumentException('Expecting T_TYPE at the beginning of a new path-part.');
            }
            return null;
        }
        return $this->match(self::T_ATTRIBUTE);
    }

    private function parsePosition(): int
    {
        if ($this->lexer->isNext(self::T_COMPONENT_SEP)) {
            $this->match(self::T_COMPONENT_SEP);
            return $this->match(self::T_POSITION);
        }
        return -1;
    }
}
