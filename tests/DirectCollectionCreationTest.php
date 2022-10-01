<?php

declare(strict_types=1);

namespace Rutek\DataclassTest;

use PHPUnit\Framework\TestCase;
use Rutek\DataclassTest\Examples\Collections\DescribedTag;
use Rutek\DataclassTest\Examples\Collections\DescribedTags;
use Rutek\DataclassTest\Examples\Collections\Tags;

use function Rutek\Dataclass\transform;

class DirectCollectionCreationTest extends TestCase
{
    public function testCollectionFromStringArray(): void
    {
        $expected = new Tags('tag1', 'tag2');
        $data = [
            'tag1',
            'tag2',
        ];
        $this->assertEquals(
            $expected,
            transform(Tags::class, $data)
        );
    }
    public function testCollectionFromObjectArray(): void
    {
        $expected = new DescribedTags(
            transform(DescribedTag::class, ['tag' => 'tag1', 'description' => 'description']),
            transform(DescribedTag::class, ['tag' => 'tag2', 'description' => 'description']),
        );
        $data = [
            ['tag' => 'tag1', 'description' => 'description'],
            ['tag' => 'tag2', 'description' => 'description'],
        ];
        $this->assertEquals(
            $expected,
            transform(DescribedTags::class, $data)
        );
    }
}
