<?php

namespace Daikon\Tests\Entity\Fixture;

use Daikon\Entity\Entity\Entity;
use Daikon\Entity\Entity\NestedEntityList;
use Daikon\Entity\ValueObject\Text;
use Daikon\Entity\ValueObject\Url;
use Daikon\Entity\ValueObject\Uuid;
use Daikon\Entity\ValueObject\ValueObjectInterface;

final class Article extends Entity
{
    /**
     * @return ValueObjectInterface
     */
    public function getIdentity(): ValueObjectInterface
    {
        return $this->getId();
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->get("id");
    }

    /**
     * @return Text
     */
    public function getTitle(): Text
    {
        return $this->get("title");
    }

    /**
     * @return Url
     */
    public function getUrl(): Url
    {
        return $this->get("url");
    }

    /**
     * @return Paragraph
     */
    public function getParagraphs(): NestedEntityList
    {
        return $this->get("paragraphs");
    }

    /**
     * @return Location
     */
    public function getWorkshopLocation(): Location
    {
        return $this->get("workshop_location");
    }
}
