<?php

declare(strict_types=1);

namespace Rutek\DataclassTest\Examples\Collections;

use Rutek\Dataclass\Collection;

class DescribedTags extends Collection
{
    public function __construct(DescribedTag ...$names)
    {
        $this->items = $names;
    }
}
