<?php

declare(strict_types=1);

namespace Rutek\DataclassTest;

use PHPUnit\Framework\TestCase;
use Rutek\Dataclass\TransformException;
use Rutek\DataclassTest\Examples\Collections\DescribedTag;
use Rutek\DataclassTest\Examples\Collections\DescribedTags;
use Rutek\DataclassTest\Examples\Collections\Product;
use Rutek\DataclassTest\Examples\Collections\Tags;
use Rutek\DataclassTest\Traits\GetErrorTrait;

use function Rutek\Dataclass\transform;

class CollectionsTest extends TestCase
{
    use GetErrorTrait;

    public function testMissingCollectionsValues(): void
    {
        $errors = [
            $this->getError('tags', 'Field must have value'),
            $this->getError('describedTags', 'Field must have value'),
        ];
        $expected = new TransformException(...$errors);

        $thrown = false;
        try {
            transform(Product::class, ['name' => 'Product name']);
        } catch (TransformException $e) {
            $thrown = true;
            $this->assertEquals($expected, $e);
        }
        $this->assertTrue($thrown);
    }

    public function testEmptyCollections(): void
    {
        $expected = new Product;
        $expected->name = 'Product name';
        $expected->tags = new Tags();
        $expected->describedTags = new DescribedTags();

        /** @var Product $obj */
        $obj = transform(Product::class, [
            'name' => 'Product name',
            'tags' => [],
            'describedTags' => []
        ]);
        $this->assertEquals($expected, $obj);
    }

    public function testFilledCollections(): void
    {
        $firstTag = new DescribedTag;
        $firstTag->tag = 'tag1';
        $firstTag->description = 'tag1 description';

        $secondTag = new DescribedTag;
        $secondTag->tag = 'tag2';
        $secondTag->description = 'tag2 description';

        $expected = new Product;
        $expected->name = 'Product name';
        $expected->tags = new Tags('tag1', 'tag2');
        $expected->describedTags = new DescribedTags($firstTag, $secondTag);

        /** @var Product $obj */
        $obj = transform(Product::class, [
            'name' => 'Product name',
            'tags' => ['tag1', 'tag2'],
            'describedTags' => [
                ['tag' => 'tag1', 'description' => 'tag1 description'],
                ['tag' => 'tag2', 'description' => 'tag2 description'],
            ]
        ]);
        $this->assertEquals($expected, $obj);
    }
}
