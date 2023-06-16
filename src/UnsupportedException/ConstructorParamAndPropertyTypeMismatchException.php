<?php

declare(strict_types=1);

namespace Rutek\Dataclass\UnsupportedException;

use Rutek\Dataclass\UnsupportedException;

class ConstructorParamAndPropertyTypeMismatchException extends UnsupportedException
{
    public function __construct(string $paramName)
    {
        parent::__construct(
            'Constructor parameter $' . $paramName . ' has a different type than the '
            . 'property of the class using the same name. Please make sure that types match.'
        );
    }
}
