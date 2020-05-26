<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/entity project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Entity;

use Assert\Assert;
use Daikon\ValueObject\ValueObjectListTrait;

trait EntityListTrait
{
    use ValueObjectListTrait;

    /** @param null|array $state */
    public static function fromNative($state): self
    {
        Assert::that($state)->nullOr()->isTraversable(
            'State provided to '.static::class.' must be null or iterable'
        );

        $entities = [];
        $typeFactories = static::getTypeFactories();
        if (!is_null($state)) {
            foreach ($state as $data) {
                Assert::that($data)->keyExists(EntityInterface::TYPE_KEY, 'Entity state is missing type key.');
                $entityType = $data[EntityInterface::TYPE_KEY];
                $entities[] = call_user_func($typeFactories[$entityType], $data);
            }
        }

        return new static($entities);
    }

    public function diff(EntityListInterface $list): self
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
