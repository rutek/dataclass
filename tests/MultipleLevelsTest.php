<?php

declare(strict_types=1);

namespace Rutek\DataclassTest;

use PHPUnit\Framework\TestCase;
use Rutek\Dataclass\TransformException;
use Rutek\DataclassTest\Examples\MultipleLevels\FirstLevel;
use Rutek\DataclassTest\Examples\MultipleLevels\SecondLevel;
use Rutek\DataclassTest\Examples\MultipleLevels\ThirdLevel;
use Rutek\DataclassTest\Traits\GetErrorTrait;

use function Rutek\Dataclass\transform;

class MultipleLevelsTest extends TestCase
{
    use GetErrorTrait;

    public function testEmptyCollections(): void
    {
        $first = new FirstLevel();
        $first->name = 'Level 1';

        $second = new SecondLevel();
        $second->level1 = $first;
        $second->name = 'Level 2';

        $third = new ThirdLevel();
        $third->level2 = $second;
        $third->name = 'Level 3';

        /** @var ThirdLevel $obj */
        $obj = transform(ThirdLevel::class, [
            'name' => 'Level 3',
            'level2' => [
                'name' => 'Level 2',
                'level1' => [
                    'name' => 'Level 1',
                ],
            ],
        ]);

        $this->assertEquals($third, $obj);
    }

    public function testFieldNamesInErrors(): void
    {
        $errors = [
            $this->getError('level2.name', 'Field must have value'),
            $this->getError('level2.level1.name', 'Field must have value'),
        ];
        $expected = new TransformException(...$errors);

        $thrown = false;
        try {
            transform(ThirdLevel::class, [
                'name' => null, // ?string = null
                'level2' => [
                    // missing: ?string $name (no default)
                    'level1' => [
                        // missing: string $name
                    ],
                ],
            ]);
        } catch (TransformException $e) {
            $thrown = true;
            $this->assertEquals($expected, $e);
        }
        $this->assertTrue($thrown);
    }

    public function testReceivesScalarWhenArrayIsExpected(): void
    {
        $errors = [
            $this->getError('level2', 'Field value must be an array'),
        ];
        $expected = new TransformException(...$errors);

        $thrown = false;
        try {
            transform(ThirdLevel::class, [
                'name' => null, // ?string = null
                'level2' => 'test', // should be an array to map to object
            ]);
        } catch (TransformException $e) {
            $thrown = true;
            $this->assertEquals($expected, $e);
        }
        $this->assertTrue($thrown);
    }
}
