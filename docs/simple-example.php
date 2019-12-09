<?php

declare(strict_types=1);

/**
 * Example: embeded class inside transformed main class
 */

namespace Test;

use function Rutek\Dataclass\transform;

include dirname(__DIR__) . '/vendor/autoload.php';

class MyEmbededClass
{
    public float $number;
}

class MyClass
{
    public int $number;
    public ?string $optionalText = null;
    public MyEmbededClass $embeded;
}

$data = '{
    "number": 1,
    "embeded": {
        "number": 1.23
    }
}';
$object = transform(MyClass::class, json_decode($data, true));

var_dump($object);
/*
object(Test\MyClass)#9 (3) {
  ["number"]=>
  int(1)
  ["optionalText"]=>
  NULL
  ["embeded"]=>
  object(Test\MyEmbededClass)#12 (1) {
    ["number"]=>
    float(1.23)
  }
}
*/
