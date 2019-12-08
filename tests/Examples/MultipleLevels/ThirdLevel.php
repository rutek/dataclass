<?php

declare(strict_types=1);

namespace Rutek\DataclassTest\Examples\MultipleLevels;

class ThirdLevel
{
    public ?string $name = null;
    public SecondLevel $level2;
}
