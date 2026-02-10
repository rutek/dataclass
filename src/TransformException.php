<?php

declare(strict_types=1);

namespace Rutek\Dataclass;

use JsonSerializable;

/**
 * Data transform failure due to not matching defined type hints
 */
class TransformException extends \Exception implements JsonSerializable
{
    /** @var FieldError[] */
    private $errors;

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

    public function jsonSerialize()
    {
        return [
            'errors' => $this->errors,
        ];
    }
}
