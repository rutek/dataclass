<?php

declare(strict_types=1);

namespace Rutek\DataclassTest\Traits;

use Rutek\Dataclass\FieldError;

trait GetErrorTrait
{
    protected function getError(string $field, string $reason): FieldError
    {
        $error = new FieldError();
        $error->field = $field;
        $error->reason = $reason;
        return $error;
    }
}
