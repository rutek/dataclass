<?php

declare(strict_types=1);

/**
 * Example: type-hinting data validation
 */

namespace Test;

use Rutek\Dataclass\TransformException;

use function Rutek\Dataclass\transform;

include dirname(__DIR__) . '/vendor/autoload.php';

class MyEmbededClass
{
    public float $number;
}

class MyClass
{
    public int $number;
    public ?string $optionalText;
    public MyEmbededClass $embeded;
}

$data = '{"number": 1}';
try {
    transform(MyClass::class, json_decode($data, true));
} catch (TransformException $e) {
    echo json_encode($e, JSON_PRETTY_PRINT) . PHP_EOL;
    /*
    {
        "errors": [
            {
                "field": "optionalText",
                "reason": "Field must have value"
            },
            {
                "field": "embeded",
                "reason": "Field must have value"
            }
        ]
    }
    */
}
