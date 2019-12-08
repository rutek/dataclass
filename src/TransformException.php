<?php

declare(strict_types=1);

namespace Rutek\Dataclass;

class TransformException extends \Exception
{
    public function __construct(FieldError ...$errors)
    {
        $this->errors = $errors;
        parent::__construct('Object cannot be created, found errors');
    }

    /**
     * Get all detected errors
     *
     * @return FieldError[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
