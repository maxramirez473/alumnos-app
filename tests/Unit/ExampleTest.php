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

    public function test_addition()
    {
        $this->assertEquals(2, 1 + 1);
    }

    public function test_subtraction()
    {
        $this->assertEquals(0, 1 - 1);
    }

    public function test_multiplication()
    {
        $this->assertEquals(4, 2 * 2);
    }

    public function test_division()
    {
        $this->assertEquals(2, 4 / 2);
    }

    public function test_modulus()
    {
        $this->assertEquals(1, 5 % 2);
    }

    public function test_string_concatenation()
    {
        $this->assertEquals('Hello, World!', 'Hello, ' . 'World!');
    }

    public function test_array_merge()
    {
        $this->assertEquals(
            ['a', 'b', 'c', 'd'],
            array_merge(['a', 'b'], ['c', 'd'])
        );
    }       
}
