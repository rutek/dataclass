<?php

declare(strict_types=1);

namespace Rutek\DataclassTest\Examples\Collections;

class Product
{
    public string $name;
    public Tags $tags;
    public DescribedTags $describedTags;
}
