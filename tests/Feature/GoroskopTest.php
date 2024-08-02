<?php

namespace Tests\Feature;
use Tests\TestCase;

class GoroskopTest extends TestCase
{
    /**
     * Test a goroskop main page
     */
    public function test_goroskop_main_page(): void
    {
        $_SERVER['REQUEST_URI'] = '/goroskop.html';
        $response = $this->get($_SERVER['REQUEST_URI']);
        $response->assertStatus(200);
    }
}
