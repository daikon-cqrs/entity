<?php

namespace Accordia\Tests\Entity\Fixture;

use Accordia\Entity\EntityType\Attribute;
use Accordia\Entity\EntityType\AttributeMap;
use Accordia\Entity\EntityType\EntityType;
use Accordia\Entity\EntityType\NestedEntityAttribute;
use Accordia\Entity\EntityType\NestedEntityListAttribute;
use Accordia\Entity\Entity\TypedEntityInterface;
use Accordia\Entity\ValueObject\Boolean;
use Accordia\Entity\ValueObject\Date;
use Accordia\Entity\ValueObject\Decimal;
use Accordia\Entity\ValueObject\Email;
use Accordia\Entity\ValueObject\GeoPoint;
use Accordia\Entity\ValueObject\Integer;
use Accordia\Entity\ValueObject\Text;
use Accordia\Entity\ValueObject\Timestamp;
use Accordia\Entity\ValueObject\Url;
use Accordia\Entity\ValueObject\Uuid;

final class ArticleType extends EntityType
{
    public function __construct()
    {
        parent::__construct("Article", [
            Attribute::define("id", Uuid::class, $this),
            Attribute::define("created", Timestamp::class, $this),
            Attribute::define("title", Text::class, $this),
            Attribute::define("url", Url::class, $this),
            Attribute::define("feedback_mail", Email::class, $this),
            Attribute::define("average_voting", Decimal::class, $this),
            Attribute::define("workshop_date", Date::class, $this),
            Attribute::define("workshop_cancelled", Boolean::class, $this),
            NestedEntityAttribute::define("workshop_location", [ LocationType::class ], $this),
            NestedEntityListAttribute::define("paragraphs", [ ParagraphType::class ], $this)
        ]);
    }

    /**
     * @inheritDoc
     */
    public function makeEntity(array $entityState = [], TypedEntityInterface $parent = null): TypedEntityInterface
    {
        $entityState["@type"] = $this;
        $entityState["@parent"] = $parent;
        return Article::fromArray($entityState);
    }
}
