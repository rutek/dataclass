<?php

declare(strict_types=1);

namespace Rutek\DataclassTest\Examples;

class ObjectWithConstructorWithAllFields extends ScalarsHinting
{
    public function __construct(
        int $number,
        ?int $numberAllowNull,
        string $text,
        ?string $textAllowNull,
        float $decimalNumber,
        ?float $decimalNumberAllowNull,
        bool $logicValue,
        ?int $optionalNumber = null,
        ?string $optionalText = null,
        ?float $optionalDecimalNumber = null
    ) {
        $this->number = $number;
        $this->numberAllowNull = $numberAllowNull;
        $this->text = $text;
        $this->textAllowNull = $textAllowNull;
        $this->decimalNumber = $decimalNumber;
        $this->decimalNumberAllowNull = $decimalNumberAllowNull;
        $this->logicValue = $logicValue;
        $this->optionalNumber = $optionalNumber;
        $this->optionalText = $optionalText;
        $this->optionalDecimalNumber = $optionalDecimalNumber;
    }
}
