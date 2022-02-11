<?php

declare(strict_types=1);

namespace Rutek\Dataclass;

use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * Base class which should be used for creating type-hinted arrays
 *
 * Extending class must contain constructor with type-hinted deconstruction of
 * nested items, for example:
 * __construct(string ...$items)
 * and assign them to $items field.
 * @template T
 * @implements IteratorAggregate<int, T>
 */
abstract class Collection implements IteratorAggregate, JsonSerializable
{
    /** @var T[] */
    protected $items = [];

    /** @return Traversable<int, T> */
    public function getIterator(): \Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function jsonSerialize()
    {
        return $this->items;
    }
}
