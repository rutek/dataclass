<?php

declare(strict_types=1);

namespace Rutek\DataclassTest\Examples;

/** Object with constructor that has different type than property. */
class ObjectWithTypeMismatchInConstructor extends ScalarsHinting
{
    public function __construct(float $number)
    {
        $this->number = (int)$number;
    }
}
