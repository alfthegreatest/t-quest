<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\MathService;

class OwnClassTest extends TestCase
{
    public function test_multiplication()
    {
        $ms = new MathService();
        $result = $ms->multiplication(2, 3);
        $this->assertEquals(6, $result);
    }
}
