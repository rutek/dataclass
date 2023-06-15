<?php

declare(strict_types=1);

namespace Rutek\DataclassTest;

use PHPUnit\Framework\TestCase;
use Rutek\DataclassTest\Examples\ObjectWithConstructor;

use function Rutek\Dataclass\transform;

class ObjectWithConstructorTest extends TestCase
{
    public function testCanCreateObjectWithConstructor(): void
    {
        /** @var ObjectWithConstructor $obj */
        $obj = transform(ObjectWithConstructor::class, [
            'number' => 1,
            'optionalNumber' => 2,
            'numberAllowNull' => 3,
            'text' => 'Some text',
            'optionalText' => 'Some second text',
            'textAllowNull' => 'Some third text',
            'decimalNumber' => 1.23,
            'optionalDecimalNumber' => 2.23,
            'decimalNumberAllowNull' => 3.23,
            // PHP does not support ?bool type so we don't check null here
            'logicValue' => true,
        ]);

        $expected = new ObjectWithConstructor(1, 'Some text', 1.23, true);
        $expected->optionalNumber = 2;
        $expected->numberAllowNull = 3;

        $expected->optionalDecimalNumber = 2.23;
        $expected->decimalNumberAllowNull = 3.23;

        $expected->optionalText = 'Some second text';
        $expected->textAllowNull = 'Some third text';

        $this->assertEquals($expected, $obj);
    }
}
