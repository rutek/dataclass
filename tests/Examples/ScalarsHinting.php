<?php

declare(strict_types=1);

namespace Rutek\DataclassTest\Examples;

class ScalarsHinting
{
    public int $number;
    public ?int $numberAllowNull;
    public ?int $optionalNumber = null;
    public string $text;
    public ?string $textAllowNull;
    public ?string $optionalText = null;
    public float $decimalNumber;
    public ?float $decimalNumberAllowNull;
    public ?float $optionalDecimalNumber = null;
    public bool $logicValue;
}
