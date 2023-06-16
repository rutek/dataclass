<?php

declare(strict_types=1);

namespace Rutek\DataclassTest\Examples;

/**
 * Object that does not meed library needs. It contains constructor with required parameter that is not a part
 * of public properties of the class.
 */
class ObjectWithInvalidFieldInConstructor extends ScalarsHinting
{
    private int $someOtherField; // @phpstan-ignore-line

    public function __construct(int $someOtherField)
    {
        $this->someOtherField = $someOtherField;
    }
}
