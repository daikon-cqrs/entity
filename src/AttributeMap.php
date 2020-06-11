<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Entity;

use Daikon\DataStructure\TypedMap;
use Daikon\Interop\Assertion;

final class AttributeMap extends TypedMap
{
    public function __construct(iterable $attributes = [])
    {
        $mappedAttributes = [];
        /** @var AttributeInterface $attribute */
        foreach ($attributes as $attribute) {
            $name = $attribute->getName();
            Assertion::keyNotExists($mappedAttributes, $name, "Attribute name '$name' is already defined.");
            $mappedAttributes[$name] = $attribute;
        }

        $this->init($mappedAttributes, [AttributeInterface::class]);
    }
}
