<?php

namespace Accordia\Tests\Entity\Fixture;

use Accordia\Entity\EntityType\Attribute;
use Accordia\Entity\EntityType\AttributeInterface;
use Accordia\Entity\EntityType\AttributeMap;
use Accordia\Entity\EntityType\EntityType;
use Accordia\Entity\Entity\TypedEntityInterface;
use Accordia\Entity\ValueObject\Integer;
use Accordia\Entity\ValueObject\Text;

final class ParagraphType extends EntityType
{
    public function __construct(AttributeInterface $parentAttribute)
    {
        parent::__construct("Paragraph", [
            Attribute::define("id", Integer::class, $this),
            Attribute::define("kicker", Text::class, $this),
            Attribute::define("content", Text::class, $this)
        ], $parentAttribute);
    }

    /**
     * @inheritDoc
     */
    public function makeEntity(array $entityState = [], TypedEntityInterface $parent = null): TypedEntityInterface
    {
        $entityState["@type"] = $this;
        $entityState["@parent"] = $parent;
        return Paragraph::fromArray($entityState);
    }
}
