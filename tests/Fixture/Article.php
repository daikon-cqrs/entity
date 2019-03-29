<?php

namespace Daikon\Tests\Entity\Fixture;

use Daikon\Entity\Entity\Attribute;
use Daikon\Entity\Entity\AttributeMap;
use Daikon\Entity\Entity\EntityInterface;
use Daikon\Entity\Entity\EntityTrait;
use Daikon\ValueObject\BoolValue;
use Daikon\ValueObject\Date;
use Daikon\ValueObject\Email;
use Daikon\ValueObject\FloatValue;
use Daikon\ValueObject\Text;
use Daikon\ValueObject\Timestamp;
use Daikon\ValueObject\Url;
use Daikon\ValueObject\Uuid;
use Daikon\ValueObject\ValueObjectInterface;

final class Article implements EntityInterface
{
    use EntityTrait;

    public static function getAttributeMap(): AttributeMap
    {
        return new AttributeMap([
            Attribute::define('id', Uuid::class),
            Attribute::define('created', Timestamp::class),
            Attribute::define('title', Text::class),
            Attribute::define('url', Url::class),
            Attribute::define('feedbackMail', Email::class),
            Attribute::define('averageVoting', FloatValue::class),
            Attribute::define('workshopDate', Date::class),
            Attribute::define('workshopCancelled', BoolValue::class),
            Attribute::define('workshopLocation', Location::class),
            Attribute::define('paragraphs', ParagraphList::class)
        ]);
    }

    public function getIdentity(): ValueObjectInterface
    {
        return $this->getId();
    }

    public function getId(): Uuid
    {
        return $this->get('id');
    }

    public function getTitle(): Text
    {
        return $this->get('title');
    }

    public function getUrl(): Url
    {
        return $this->get('url');
    }

    public function getFeedbackMail(): Email
    {
        return $this->get('feedbackMail');
    }

    public function getAverageVoting(): FloatValue
    {
        return $this->get('averageVoting');
    }

    public function getWorkshopDate(): Date
    {
        return $this->get('workshopDate');
    }

    public function getWorkshopLocation(): Location
    {
        return $this->get('workshopLocation');
    }

    public function isWorkshopCancelled(): BoolValue
    {
        return $this->get('workshopCancelled') ?? BoolValue::fromNative(false);
    }

    public function getParagraphs(): ParagraphList
    {
        return $this->get('paragraphs') ?? ParagraphList::makeEmpty();
    }
}
