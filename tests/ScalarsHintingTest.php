<?php

declare(strict_types=1);

namespace Rutek\DataclassTest;

use PHPUnit\Framework\TestCase;
use Rutek\Dataclass\Transform;
use Rutek\Dataclass\TransformException;
use Rutek\DataclassTest\Examples\ScalarsHinting;
use Rutek\DataclassTest\Traits\GetErrorTrait;

class ScalarsHintingTest extends TestCase
{
    use GetErrorTrait;

    public function testCreationWithNullsAllowed()
    {
        $transformer = new Transform();
        /** @var TestClass $obj */
        $obj = $transformer->to(ScalarsHinting::class, [
            'number' => 1,
            'numberAllowNull' => null,
            'text' => 'Some text',
            'textAllowNull' => null,
            'decimalNumber' => 1.23,
            'decimalNumberAllowNull' => null,
            // PHP does not support ?bool type so we don't check null here
            'logicValue' => false,
        ]);

        $expected = new ScalarsHinting();
        $expected->number = 1;
        $expected->optionalNumber = null;
        $expected->numberAllowNull = null;

        $expected->decimalNumber = 1.23;
        $expected->optionalDecimalNumber = null;
        $expected->decimalNumberAllowNull = null;

        $expected->text = 'Some text';
        $expected->optionalText = null;
        $expected->textAllowNull = null;

        $expected->logicValue = false;

        $this->assertEquals($expected, $obj);
    }

    public function testCreationWithOptionalFields()
    {
        $transformer = new Transform();
        /** @var TestClass $obj */
        $obj = $transformer->to(ScalarsHinting::class, [
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

        $expected = new ScalarsHinting();
        $expected->number = 1;
        $expected->optionalNumber = 2;
        $expected->numberAllowNull = 3;

        $expected->decimalNumber = 1.23;
        $expected->optionalDecimalNumber = 2.23;
        $expected->decimalNumberAllowNull = 3.23;

        $expected->text = 'Some text';
        $expected->optionalText = 'Some second text';
        $expected->textAllowNull = 'Some third text';

        $expected->logicValue = true;

        $this->assertEquals($expected, $obj);
    }

    public function testRequiredData()
    {
        // Also fields with type-hinting "?scalar" must be defined as they
        // have no default value
        $errors = [
            $this->getError('number', 'Field must have value'),
            $this->getError('numberAllowNull', 'Field must have value'),
            $this->getError('text', 'Field must have value'),
            $this->getError('textAllowNull', 'Field must have value'),
            $this->getError('decimalNumber', 'Field must have value'),
            $this->getError('decimalNumberAllowNull', 'Field must have value'),
            // PHP does not support ?bool type so we don't check null here
            $this->getError('logicValue', 'Field must have value'),
        ];
        $expected = new TransformException(...$errors);

        $thrown = false;
        try {
            $transformer = new Transform();
            $transformer->to(ScalarsHinting::class, []);
        } catch (TransformException $e) {
            $thrown = true;
            $this->assertEquals($expected, $e);
        }
        $this->assertTrue($thrown);
    }
}
