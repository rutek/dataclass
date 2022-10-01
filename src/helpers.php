<?php

declare(strict_types=1);

namespace Rutek\Dataclass;

/**
 * Simple way to transform received data array to strictly type-hinted class
 *
 * @template T of object
 * @param class-string<T> $class
 * @param mixed $data
 * @return T Data of type $class
 * @throws TransformException
 * @throws UnsupportedException
 */
function transform(string $class, $data)
{
    $transform = new Transform();
    return $transform->to($class, $data);
}
