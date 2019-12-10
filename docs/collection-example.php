<?php

declare(strict_types=1);

/**
 * Example: object Transformer and it's transform() alias
 */

namespace Test;

use Rutek\Dataclass\Collection;

use function Rutek\Dataclass\transform;

include dirname(__DIR__) . '/vendor/autoload.php';

class Tag
{
    public string $name;
}

class Tags extends Collection
{
    public function __construct(Tag ...$tag)
    {
        $this->items = $tag;
    }
}

class Product
{
    public Tags $tags;
}

// You can use library for creation of objects with nested collections
$data = '{"tags": [
    {"name": "tag1"}
]}';
$object = transform(Product::class, json_decode($data, true));
var_dump($object);
/*
object(Test\Product)#7 (1) {
  ["tags"]=>
  object(Test\Tags)#10 (1) {
    ["items":protected]=>
    array(1) {
      [0]=>
      object(Test\Tag)#13 (1) {
        ["name"]=>
        string(4) "tag1"
      }
    }
  }
}
*/

// ... and also to directly create Collection from array
$data = '[
    {"name": "tag1"}
]';
$object = transform(Tags::class, json_decode($data, true));
var_dump($object);
/*
object(Test\Tags)#8 (1) {
  ["items":protected]=>
  array(1) {
    [0]=>
    object(Test\Tag)#11 (1) {
      ["name"]=>
      string(4) "tag1"
    }
  }
}
*/
