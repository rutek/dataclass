<?php

declare(strict_types=1);

namespace Rutek\Dataclass;

use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;

/**
 * Base class which should be used for creating type-hinted arrays
 *
 * Extending class must contain constructor with type-hinted deconstruction of
 * nested items, for example:
 * __construct(string ...$items)
 * and assign them to $items field.
 */
abstract class Collection implements IteratorAggregate, JsonSerializable
{
    protected $items = [];

    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    public function jsonSerialize()
    {
        return $this->items;
    }
}
