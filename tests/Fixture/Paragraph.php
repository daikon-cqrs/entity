<?php

namespace Accordia\Tests\Entity\Fixture;

use Accordia\Entity\Entity\NestedEntity;
use Accordia\Entity\ValueObject\Integer;
use Accordia\Entity\ValueObject\Text;
use Accordia\Entity\ValueObject\ValueObjectInterface;

final class Paragraph extends NestedEntity
{
    /**
     * @return ValueObjectInterface
     */
    public function getIdentity(): ValueObjectInterface
    {
        return $this->getId();
    }

    /**
     * @return Integer
     */
    public function getId(): Integer
    {
        return $this->get("id");
    }

    /**
     * @return Text
     */
    public function getKicker(): Text
    {
        return $this->get("kicker");
    }

    /**
     * @return Text
     */
    public function getContent(): Text
    {
        return $this->get("content");
    }
}
