<?php

declare(strict_types=1);

namespace Rutek\DataclassTest\Examples;

class ObjectWithConstructor extends ScalarsHinting
{
    /**
     * It's planned to not fill in any of properties that are required (have no default values).
     */
    public function __construct(int $number, string $text, float $decimalNumber, bool $logicValue)
    {
        $this->number = $number;
        $this->text = $text;
        $this->decimalNumber = $decimalNumber;
        $this->logicValue = $logicValue;
    }
}
