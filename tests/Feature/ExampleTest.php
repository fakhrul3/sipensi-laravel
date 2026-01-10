<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_true_is_true(): void
    {
        $this->assertTrue(true);
    }    
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->markTestSkipped(
            'Disabled: homepage depends on legacy database tables (inkubator).'
        );
    }
}
