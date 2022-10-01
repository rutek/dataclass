<?php

declare(strict_types=1);

namespace Rutek\DataclassTest;

use PHPUnit\Framework\TestCase;
use Rutek\Dataclass\FieldError;
use Rutek\Dataclass\TransformException;
use Rutek\DataclassTest\Examples\Collections\Product;

use function Rutek\Dataclass\transform;

class NullDataTest extends TestCase
{
    public function testNullFromJsonDecode(): void
    {
        $expected = new TransformException(
            transform(FieldError::class, ['field' => 'root', 'reason' => 'Data could not be decoded'])
        );

        $thrown = false;
        try {
            transform(Product::class, json_decode('some broken json', true));
        } catch (TransformException $e) {
            $thrown = true;
            $this->assertEquals($expected, $e);
        }
        $this->assertTrue($thrown);
    }
}
