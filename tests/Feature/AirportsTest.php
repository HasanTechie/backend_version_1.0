<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AirportsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    use DatabaseMigrations;

    /** @test */
    public function a_user_can_browse_airports()
    {
        $response = $this->get('/flights/');

        $response->assertStatus(200);
    }
}
