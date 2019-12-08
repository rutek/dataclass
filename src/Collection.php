<?php

declare(strict_types=1);

namespace Rutek\Dataclass;

use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;

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
