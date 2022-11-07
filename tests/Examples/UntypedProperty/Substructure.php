<?php

declare(strict_types=1);

namespace Rutek\DataclassTest\Examples\UntypedProperty;

class Substructure
{
    public int $num;
    // @phpstan-ignore-next-line
    public $anytype;
}
