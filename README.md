# PHP Dataclass library

Dataclass allows you to quickly change your plain `array` to type-hinted PHP class with automatic denormalization of embeded objects and collections which normally requires much work if you use normalizers and denormalizers. Library is insipired by
Python [pydantic](https://pydantic-docs.helpmanual.io/) module. It uses type-hinting power available since PHP 7.4.

Main goal of this package is to provide fast way for having strictly type-hinted classes for further usage, for example to map request payload to strictly typed class so you can use it instead of array which may or may not match your requirements. It won't replace your data validation but will make you sure that f.x. received JSON payload matches types which your backend operations expect to receive. It's something like mentioned above [pydantic `BaseModel`](https://pydantic-docs.helpmanual.io/) or [TypeScript interface](https://www.typescriptlang.org/docs/handbook/interfaces.html).

All you need is create class or two:

```php
declare(strict_types=1);

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
```

As next step pass main class name and received data, for example from received JSON to `transform` method:

```php
$data = '{
    "number": 1,
    "embeded": {
        "number": 1.23
    }
}';
$object = transform(MyClass::class, json_decode($data, true));
```

To quickly map received data to fully functional dataclass:

```php
var_dump($object)
```

```php
object(MyClass) {
  ["number"]=> int(1)
  ["optionalText"]=> NULL
  ["embeded"]=>
  object(MyEmbededClass) {
    ["number"]=>
    float(1.23)
  }
}
```

You don't have to worry about missing fields and invalid types as library detects all type-hinted requirements and throws `TransformException` with errors (ready to be served as response) pointing to exact fields with simple reason message, for example:

```php
echo json_encode($transformException, JSON_PRETTY_PRINT)
```

```json
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
```

You can also use `Transform::to` method which is in fact called by `transform` helper function. Helper function will always use optimal settings for `Transform` objects (as soon as they appear).

```php
$data = '{
    "number": 1,
    "embeded": {
        "number": 1.23
    }
}';
$transformer = new Transform();
$object = $transformer->to(MyClass::class, json_decode($data, true));
```

### More examples

Please check out [docs/ directory](docs/) for more exxamples.

## Installation

As simple as

```
composer install rutek/dataclass
```

## Supported type hints

**Attention:** please be aware of using `array` type hints. They cannot be used (will throw `UnsupportedException` if detected) as PHP does not provide way to type-hint items of array. Please check *Collections* section below for further information.

### Scalars

All [four PHP scalars](https://www.php.net/manual/en/language.types.intro.php) are supported.

### Nullable fields

Type-hinting nullability is supported. You can safely use for example `?string` to accept both `string` and `null`. Please be aware as using only `?string $field` does not mean that transformed array may not contain this field. It only means that this value accepts `null`.

### Default values

If you need to accept transformation of data which does not contain some fields you can use default values, for exmaple: `?string $field = null`. Dataclass library will detect that this property does not exist in payload and will use default value instead.

### Collections

PHP does not support type-hinting `array` fields if you need to embed collection of objects or scalars you have to use `Collection` class. You need to extend it with constructor with type-hinted arguments deconstruction, for example:

```php
class Tags extends Collection
{
    public function __construct(string ...$names)
    {
        $this->items = $names;
    }
}
```

Type-hinted arrays like `string[]` [were rejected in RFC](https://wiki.php.net/rfc/arrayof) so propably this behaviour will not change soon.

Library will check if provided values match type-hinting of constructor.

There is no possiblity to check minimum and maxiumum items but there may be such feature in next versions.

## Exceptions

## `TransformException` - data does not match your schema

This is base exception you can expect. Every time your data (payload) passed to function `transform(string $class, $data)` or `Transform::to` will not match your type-hinted classes, you will receive `TransformException` with `getErrors(): FieldError[]` method which describes what really happened.

Every `FieldError` contains both `field` describing which field failed type check and `reason` describing in simple words why it has been rejected. If there are nested objects you can expect to receive `field` values like `parentProperty.childrenProperty` (levels separated by dot).

Class supports JSON serialization and will always return something like:

```json
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
```

Please note that `code` field may be added in future.

## `UnsupportedException` - only if your type-hints are not supported

Library does not cover all scenarios as you can define type hints which would not have strict context. For example if you use `object` property, it would not be possible to validate it as any object will match your schema. In such cases you can expect `UnsupportedException`.

## Unsupported (yet?)

### Private and protected properties

All type-hinted fields must be public as for now. Implementing such feature is questionable as you would have to create getters for such properties which is unwanted overhead. Library is meant to create possiblity to define internal schemas for data received from remote systems (APIs, queues/bus messages, browsers).

### Reflection cache

All reflection checks are made every time `transform` or `Transform::to` function is called. You can expect caching functionality for better performance soon.

## Remarks

Please remember that goal of this package is not to fully validate data you receive but to create simple classes which makes your payloads fully type-hinted also when they have some complicated structure. You will not have to encode you classes in JSON with `class` fields as your type hints will tell your code what object or array should be created in place of some embeded values.

If you are creating API and you need enterprise-grade OpenAPI schema validation you should check [hkarlstrom/openapi-validation-middleware](https://github.com/hkarlstrom/openapi-validation-middleware) and afterwards you can map received payload to type-hinted classes using this library! :)

