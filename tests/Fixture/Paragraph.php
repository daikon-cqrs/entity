<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Tests\Entity\Fixture;

use Daikon\Entity\Attribute;
use Daikon\Entity\AttributeMap;
use Daikon\Entity\EntityInterface;
use Daikon\Entity\EntityTrait;
use Daikon\ValueObject\IntValue;
use Daikon\ValueObject\Text;
use Daikon\ValueObject\ValueObjectInterface;

final class Paragraph implements EntityInterface
{
    use EntityTrait;

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
        return $this->get('id') ?? IntValue::zero();
    }

    public function getKicker(): Text
    {
        return $this->get('kicker') ?? Text::makeEmpty();
    }

    public function getContent(): Text
    {
        return $this->get('content') ?? Text::makeEmpty();
    }
}
