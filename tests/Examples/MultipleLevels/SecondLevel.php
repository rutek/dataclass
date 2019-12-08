<?php

declare(strict_types=1);

namespace Rutek\DataclassTest\Examples\MultipleLevels;

class SecondLevel
{
    public ?string $name;
    public FirstLevel $level1;
}
