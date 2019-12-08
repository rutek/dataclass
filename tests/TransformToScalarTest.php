<?php

declare(strict_types=1);

namespace Rutek\DataclassTest;

use PHPUnit\Framework\TestCase;
use Rutek\Dataclass\Transform;
use Rutek\Dataclass\TransformException;

class TransformToScalarTest extends TestCase
{
    public function testInteger()
    {
        $transform = new Transform();
        $this->assertSame(123, $transform->to('int', 123));
    }

    public function testNotAnInteger()
    {
        $this->expectException(TransformException::class);

        $transform = new Transform();
        $transform->to('int', 123.01);
    }

    public function testFloat()
    {
        $transform = new Transform();
        $this->assertSame(123.01, $transform->to('float', 123.01));
    }

    public function testNotAFloat()
    {
        $this->expectException(TransformException::class);

        $transform = new Transform();
        $transform->to('float', 123);
    }

    public function testString()
    {
        $transform = new Transform();
        $this->assertSame('text', $transform->to('string', 'text'));
    }

    public function testNotAString()
    {
        $this->expectException(TransformException::class);

        $transform = new Transform();
        $transform->to('string', 123);
    }

    public function testBool()
    {
        $transform = new Transform();
        $this->assertSame(true, $transform->to('bool', true));
    }

    public function testNotABool()
    {
        $this->expectException(TransformException::class);

        $transform = new Transform();
        $transform->to('bool', 123);
    }
}
