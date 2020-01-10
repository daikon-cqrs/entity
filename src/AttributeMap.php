<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Entity;

use Assert\Assert;
use Daikon\DataStructure\TypedMapInterface;
use Daikon\DataStructure\TypedMapTrait;

final class AttributeMap implements TypedMapInterface
{
    use TypedMapTrait;

    public function __construct(iterable $attributes = [])
    {
        $mappedAttributes = [];
        /** @var AttributeInterface $attribute */
        foreach ($attributes as $attribute) {
            $attributeName = $attribute->getName();
            Assert::that($mappedAttributes)->keyNotExists(
                $attributeName,
                "Attribute name '$attributeName' is already defined."
            );
            $mappedAttributes[$attributeName] = $attribute;
        }

        $this->init($mappedAttributes, [AttributeInterface::class]);
    }
}
