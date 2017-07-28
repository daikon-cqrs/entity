<?php

namespace Daikon\Tests\Entity\Fixture;

use Daikon\Entity\Entity\EntityInterface;
use Daikon\Entity\EntityType\Attribute;
use Daikon\Entity\EntityType\EntityType;
use Daikon\Entity\EntityType\NestedEntityAttribute;
use Daikon\Entity\EntityType\NestedEntityListAttribute;
use Daikon\Entity\ValueObject\Boolean;
use Daikon\Entity\ValueObject\Date;
use Daikon\Entity\ValueObject\Decimal;
use Daikon\Entity\ValueObject\Email;
use Daikon\Entity\ValueObject\Text;
use Daikon\Entity\ValueObject\Timestamp;
use Daikon\Entity\ValueObject\Url;
use Daikon\Entity\ValueObject\Uuid;

final class ArticleType extends EntityType
{
    public static function getName(): string
    {
        return 'Article';
    }

    public function __construct()
    {
        parent::__construct([
            Attribute::define('id', Uuid::class, $this),
            Attribute::define('created', Timestamp::class, $this),
            Attribute::define('title', Text::class, $this),
            Attribute::define('url', Url::class, $this),
            Attribute::define('feedback_mail', Email::class, $this),
            Attribute::define('average_voting', Decimal::class, $this),
            Attribute::define('workshop_date', Date::class, $this),
            Attribute::define('workshop_cancelled', Boolean::class, $this),
            NestedEntityAttribute::define('workshop_location', [ LocationType::class ], $this),
            NestedEntityListAttribute::define('paragraphs', [ ParagraphType::class ], $this)
        ]);
    }

    /**
     * @inheritDoc
     */
    public function makeEntity(array $entityState = [], EntityInterface $parent = null): EntityInterface
    {
        $entityState['@type'] = $this;
        $entityState['@parent'] = $parent;
        return Article::fromNative($entityState);
    }
}
