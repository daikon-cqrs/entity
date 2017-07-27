<?php

namespace Daikon\Tests\Entity\Fixture;

use Daikon\Entity\Entity\TypedEntityInterface;
use Daikon\Entity\EntityType\Attribute;
use Daikon\Entity\EntityType\AttributeInterface;
use Daikon\Entity\EntityType\EntityType;
use Daikon\Entity\ValueObject\Integer;
use Daikon\Entity\ValueObject\Text;

final class ParagraphType extends EntityType
{
    public static function getName(): string
    {
        return 'Paragraph';
    }

    public function __construct(AttributeInterface $parentAttribute)
    {
        parent::__construct([
            Attribute::define('id', Integer::class, $this),
            Attribute::define('kicker', Text::class, $this),
            Attribute::define('content', Text::class, $this)
        ], $parentAttribute);
    }

    /**
     * @inheritDoc
     */
    public function makeEntity(array $entityState = [], TypedEntityInterface $parent = null): TypedEntityInterface
    {
        $entityState['@type'] = $this;
        $entityState['@parent'] = $parent;
        return Paragraph::fromNative($entityState);
    }
}
