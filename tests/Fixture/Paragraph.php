<?php

namespace Daikon\Tests\Entity\Fixture;

use Daikon\Entity\Entity\NestedEntity;
use Daikon\Entity\ValueObject\Integer;
use Daikon\Entity\ValueObject\Text;
use Daikon\Entity\ValueObject\ValueObjectInterface;

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
        return $this->get('id');
    }

    /**
     * @return Text
     */
    public function getKicker(): Text
    {
        return $this->get('kicker');
    }

    /**
     * @return Text
     */
    public function getContent(): Text
    {
        return $this->get('content');
    }
}
