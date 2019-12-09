<?php

declare(strict_types=1);

/**
 * Example: object Transformer and it's transform() alias
 */

namespace Test;

use Rutek\Dataclass\Transform;

use function Rutek\Dataclass\transform;

include dirname(__DIR__) . '/vendor/autoload.php';

class MyClass
{
    public int $number;
}

$data = '{"number": 1}';

// You can use both transform() helper
$object = transform(MyClass::class, json_decode($data, true));
var_dump($object);

// and Transform::to method
$transformer = new Transform();
$object = $transformer->to(MyClass::class, json_decode($data, true));
var_dump($object); // same result
