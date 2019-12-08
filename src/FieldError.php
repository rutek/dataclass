<?php

declare(strict_types=1);

namespace Rutek\Dataclass;

class FieldError extends \Exception
{
    public function __construct()
    {
        parent::__construct('Field type error');
    }

    public string $field;
    public string $details;
}
