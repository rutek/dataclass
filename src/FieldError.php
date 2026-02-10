<?php

declare(strict_types=1);

namespace Rutek\Dataclass;

use JsonSerializable;

/**
 * Description of error which occured during type checking of received data
 */
class FieldError extends \Exception implements JsonSerializable
{
    public function __construct()
    {
        parent::__construct('Field type error');
    }

    public string $field;
    public string $reason;

    public function jsonSerialize()
    {
        return [
            'field' => $this->field,
            'reason' => $this->reason,
        ];
    }
}
