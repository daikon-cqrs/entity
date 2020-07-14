<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Entity;

use Daikon\Interop\Assertion;
use Daikon\ValueObject\ValueObjectList;

/**
 * @type(Daikon\Entity\EntityInterface)
 */
class EntityList extends ValueObjectList
{
    /**
     * @param null|iterable $state
     * @return static
     */
    public static function fromNative($state): self
    {
        Assertion::nullOrIsTraversable($state, "State provided to '".static::class."' must be null or iterable.");

        $entities = [];
        $typeFactories = static::inferTypeFactories();
        if (!is_null($state)) {
            foreach ($state as $data) {
                Assertion::keyExists($data, EntityInterface::TYPE_KEY, 'Entity state is missing type key.');
                $entityType = $data[EntityInterface::TYPE_KEY];
                Assertion::isCallable($typeFactories[$entityType], "Entity factory for '$entityType' is not valid.");
                $entities[] = $typeFactories[$entityType]($data);
            }
        }

        return new static($entities);
    }

    /** @return static */
    public function diff(self $list): self
    {
        $differingEntities = [];
        foreach ($this as $pos => $entity) {
            /**
             * @psalm-suppress PossiblyNullArgument
             * @psalm-suppress ArgumentTypeCoercion
             */
            if (!$list->has($pos) || !(new EntityDiff)($entity, $list->get($pos))->isEmpty()) {
                $differingEntities[] = $entity;
            }
        }
        return new static($differingEntities);
    }
}
