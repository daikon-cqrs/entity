<?php

namespace Daikon\Tests\Entity\Fixture;

use Daikon\Entity\Entity\Attribute;
use Daikon\Entity\Entity\AttributeMap;
use Daikon\Entity\Entity\Entity;
use Daikon\Entity\ValueObject\IntValue;
use Daikon\Entity\ValueObject\Text;
use Daikon\Entity\ValueObject\ValueObjectInterface;

final class Paragraph extends Entity
{
    public static function getAttributeMap(): AttributeMap
    {
        return new AttributeMap([
            Attribute::define('id', IntValue::class),
            Attribute::define('kicker', Text::class),
            Attribute::define('content', Text::class)
        ]);
    }

    public function getIdentity(): ValueObjectInterface
    {
        return $this->getId();
    }

    public function getId(): IntValue
    {
        return $this->get('id');
    }

    public function getKicker(): Text
    {
        return $this->get('kicker');
    }

    public function getContent(): Text
    {
        return $this->get('content');
    }
}
