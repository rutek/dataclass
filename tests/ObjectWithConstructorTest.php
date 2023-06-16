<?php

declare(strict_types=1);

namespace Rutek\DataclassTest;

use PHPUnit\Framework\TestCase;
use Rutek\Dataclass\UnsupportedException;
use Rutek\DataclassTest\Examples\ObjectWithConstructor;
use Rutek\DataclassTest\Examples\ObjectWithConstructorWithAllFields;
use Rutek\DataclassTest\Examples\ObjectWithInvalidFieldInConstructor;
use Rutek\DataclassTest\Examples\ObjectWithTypeMismatchInConstructor;

use function Rutek\Dataclass\transform;

class ObjectWithConstructorTest extends TestCase
{
    /** Verification of support for objects with constructor that contains some properties in it */
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

    /** Verification of support for objects with constructor that contains all it's properties */
    public function testCanCreateObjectWithConstructorWithAllFields(): void
    {
        /** @var ObjectWithConstructorWithAllFields $obj */
        $obj = transform(ObjectWithConstructorWithAllFields::class, [
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

        $expected = new ObjectWithConstructorWithAllFields(
            1,
            3,
            'Some text',
            'Some third text',
            1.23,
            3.23,
            true,
            2,
            'Some second text',
            2.23
        );
        $this->assertEquals($expected, $obj);
    }

    /** Check if library will report that constructor is unsupported instead of throwing unexpected errors */
    public function testReportsErrorWhenUnsupportedConstructorIsDetected(): void
    {
        $this->expectException(UnsupportedException::class);
        $this->expectExceptionMessage(
            'Constructor parameter $someOtherField does not have a corresponding public '
            . 'property of the class, so it cannot be used in constructor. Please remove it from '
            . 'constructor or add it to public properties of the class.'
        );

        $obj = transform(ObjectWithInvalidFieldInConstructor::class, [
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
    }

    /** Check if library performs checks for type mismatch between property and constructor arguments */
    public function testReportsErrorWhenEncountersConstructorAndPropertyTypesMismatch(): void
    {
        $this->expectException(UnsupportedException::class);
        $this->expectExceptionMessage(
            'Constructor parameter $number has a different type than the '
            . 'property of the class using the same name.'
        );

        $obj = transform(ObjectWithTypeMismatchInConstructor::class, [
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
    }
}
