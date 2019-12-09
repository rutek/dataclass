<?php

declare(strict_types=1);

namespace Rutek\DataclassTest;

use PHPUnit\Framework\TestCase;
use Rutek\Dataclass\TransformException;

use function Rutek\Dataclass\transform;

class TransformToScalarTest extends TestCase
{
    public function testInteger()
    {
        $this->assertSame(123, transform('int', 123));
    }

    public function testNotAnInteger()
    {
        $this->expectException(TransformException::class);

        transform('int', 123.01);
    }

    public function testFloat()
    {
        $this->assertSame(123.01, transform('float', 123.01));
    }

    public function testNotAFloat()
    {
        $this->expectException(TransformException::class);

        transform('float', 123);
    }

    public function testString()
    {
        $this->assertSame('text', transform('string', 'text'));
    }

    public function testNotAString()
    {
        $this->expectException(TransformException::class);

        transform('string', 123);
    }

    public function testBool()
    {
        $this->assertSame(true, transform('bool', true));
    }

    public function testNotABool()
    {
        $this->expectException(TransformException::class);

        transform('bool', 123);
    }
}
