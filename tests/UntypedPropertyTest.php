<?php

declare(strict_types=1);

namespace Rutek\DataclassTest;

use PHPUnit\Framework\TestCase;
use Rutek\DataclassTest\Examples\UntypedProperty\Substructure;
use Rutek\DataclassTest\Examples\UntypedProperty\SubstructureCollection;
use Rutek\DataclassTest\Examples\UntypedProperty\TestRoute;

use function Rutek\Dataclass\transform;

class UntypedPropertyTest extends TestCase
{
    /** We ignore untyped properties but pass data "as they are" */
    public function testPassesUntypedPropertyDataAsIs(): void
    {
        /** @var TestRoute */
        $result = transform(TestRoute::class, [
            'substructures' => [
                ['num' => 1, 'anytype' => ['some' => 'data', 'val' => 123]],
            ]
        ]);
        $this->assertInstanceOf(TestRoute::class, $result);
        $this->assertInstanceOf(SubstructureCollection::class, $result->substructures);

        /** @var Substructure[] */
        $substructures = iterator_to_array($result->substructures);
        $this->assertCount(1, $substructures);
        $this->assertSame(1, $substructures[0]->num);
        $this->assertSame(['some' => 'data', 'val' => 123], $substructures[0]->anytype);
    }

    /** Missing data for untyped property = NULL value */
    public function testUntypedPropertyHasNullIfNoValueWasSpecified(): void
    {
        /** @var TestRoute */
        $result = transform(TestRoute::class, [
            'substructures' => [
                ['num' => 1],
            ]
        ]);
        $this->assertInstanceOf(TestRoute::class, $result);
        $this->assertInstanceOf(SubstructureCollection::class, $result->substructures);

        /** @var Substructure[] */
        $substructures = iterator_to_array($result->substructures);
        $this->assertCount(1, $substructures);
        $this->assertSame(1, $substructures[0]->num);
        $this->assertNull($substructures[0]->anytype);
    }
}
