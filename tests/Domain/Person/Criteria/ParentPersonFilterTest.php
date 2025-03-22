<?php

namespace App\Tests\Domain\Person\Criteria;

use App\Domain\Person\Criteria\ParentPersonFilter;
use PHPUnit\Framework\TestCase;

class ParentPersonFilterTest extends TestCase
{
    private const FILTER = 'test';

    public function test_first_name_filter() : void
    {
        $firstNameFilter = new ParentPersonFilter('test');
        self::assertInstanceOf(ParentPersonFilter::class, $firstNameFilter);
        self::assertEquals(self::FILTER, $firstNameFilter->value);
    }
}