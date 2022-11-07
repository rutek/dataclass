<?php

declare(strict_types=1);

namespace Rutek\DataclassTest\Examples\UntypedProperty;

use Rutek\Dataclass\Collection;

/** @extends Collection<Substructure> */
class SubstructureCollection extends Collection
{
    public function __construct(Substructure ...$str)
    {
        $this->items = $str;
    }
}
