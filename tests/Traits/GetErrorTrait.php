<?php

declare(strict_types=1);

namespace Rutek\DataclassTest\Traits;

use Rutek\Dataclass\FieldError;

trait GetErrorTrait
{
    protected function getError(string $field, string $details): FieldError
    {
        $error = new FieldError();
        $error->field = $field;
        $error->details = $details;
        return $error;
    }
}
