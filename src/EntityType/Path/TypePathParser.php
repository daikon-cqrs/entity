<?php

namespace Daikon\Entity\EntityType\Path;

use Daikon\Entity\Error\InvalidTypePath;
use JMS\Parser\AbstractParser;
use JMS\Parser\SimpleLexer;

final class TypePathParser extends AbstractParser
{
    private const T_UNKNOWN = 0;

    private const T_TYPE = 1;

    private const T_COMPONENT_SEP = 2;

    private const T_PART_SEP = 3;

    private const TOKEN_REGEX = <<<REGEX
/
    # type identifier which refers to either an attribute or entity-type
    ([a-zA-Z_]+)

    # path-part-component separator, the two components of a type-path-part being attribute and entity-type.
    |(\.)

    # path-part separator
    |(\-)
/x
REGEX;

    private const TOKEN_MAP = [
        0 => 'T_UNKNOWN',
        1 => 'T_TYPE',
        2 => 'T_COMPONENT_SEP',
        3 => 'T_PART_SEP'
    ];

    public static function create(): TypePathParser
    {
        $mapToken = function (string $token): array {
            switch ($token) {
                case '.':
                    return [ self::T_COMPONENT_SEP, $token ];
                case '-':
                    return [ self::T_PART_SEP, $token ];
                default:
                    return preg_match('/[a-z_]+/', $token)
                        ? [ self::T_TYPE, $token ]
                        : [ self::T_UNKNOWN, $token ];
            }
        };
        $lexer = new SimpleLexer(self::TOKEN_REGEX, self::TOKEN_MAP, $mapToken);
        return new TypePathParser($lexer);
    }

    public function parse($path, $context = null): TypePath
    {
        return parent::parse($path, $context);
    }

    public function parseInternal(): TypePath
    {
        $typePathParts = [];
        while ($typePathPart = $this->consumePathPart()) {
            $typePathParts[] = $typePathPart;
        }
        return new TypePath($typePathParts);
    }

    private function consumePathPart(): ?TypePathPart
    {
        if ($this->lexer->isNext(self::T_PART_SEP)) {
            $this->match(self::T_PART_SEP);
        }
        if (!$this->lexer->isNext(self::T_TYPE)) {
            if ($this->lexer->next !== null) {
                throw new InvalidTypePath('Expecting T_TYPE at the beginning of a new path-part.');
            }
            return null;
        }
        $attribute = $this->match(self::T_TYPE);
        $type = '';
        if ($this->lexer->isNext(self::T_COMPONENT_SEP)) {
            $this->match(self::T_COMPONENT_SEP);
            $type = $this->match(self::T_TYPE);
            if ($this->lexer->next === null) {
                throw new InvalidTypePath(
                    'Unexpected T_TYPE at the end of type-path. Type-paths must end pointing towards an attribute.'
                );
            }
        }
        return new TypePathPart($attribute, $type);
    }
}
