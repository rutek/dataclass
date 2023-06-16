<?php

declare(strict_types=1);

namespace Rutek\Dataclass\UnsupportedException;

use Rutek\Dataclass\UnsupportedException;

class MissingPropertyForConstructorParameterException extends UnsupportedException
{
    public function __construct(string $paramName)
    {
        parent::__construct(
            'Constructor parameter $' . $paramName . ' does not have a corresponding public '
            . 'property of the class, so it cannot be used in constructor. Please remove it from '
            . 'constructor or add it to public properties of the class.'
        );
    }
}
