<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    public function test_sum_of_two_numbers()
    {
        $mathResult = 2 + 2;
        $this->assertEquals(4, $mathResult);
    }

    public function test_multiplexion_of_two_numbers()
    {
        $mathResult = 2 * 2;
        $this->assertEquals(4, $mathResult);
    }
}
