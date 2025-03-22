<?php

namespace App\Tests\Domain\Person\Criteria;

use App\Domain\Person\Criteria\FirstNameFilter;
use PHPUnit\Framework\TestCase;

class FirstNameFilterTest extends TestCase
{

    public function test_first_name_filter() : void
    {
        $firstNameFilter = new FirstNameFilter('test');
        self::assertInstanceOf(FirstNameFilter::class, $firstNameFilter);
    }
}