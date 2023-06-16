<?php

declare(strict_types=1);

namespace Rutek\DataclassTest\Examples;

class ObjectWithConstructor extends ScalarsHinting
{
    public function __construct(int $number, string $text, float $decimalNumber, bool $logicValue)
    {
        $this->number = $number;
        $this->text = $text;
        $this->decimalNumber = $decimalNumber;
        $this->logicValue = $logicValue;
    }
}
