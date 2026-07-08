<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function test_it_adds_two_numbers_correctly(): void
    {
        $result = 2 + 3;
        $this->assertEquals(5, $result);
    }
}
