<?php

namespace Accordia\Tests\Entity\Fixture;

use Accordia\Entity\Entity\Entity;
use Accordia\Entity\Entity\NestedEntityList;
use Accordia\Entity\ValueObject\Integer;
use Accordia\Entity\ValueObject\Text;
use Accordia\Entity\ValueObject\Url;
use Accordia\Entity\ValueObject\Uuid;
use Accordia\Entity\ValueObject\ValueObjectInterface;

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
