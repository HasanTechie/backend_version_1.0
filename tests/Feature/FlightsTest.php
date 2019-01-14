<?php

namespace Tests\Feature;

use App\Flight;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FlightsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    use DatabaseMigrations;

    /** @test */
    public function a_user_can_browse_flights()
    {
        $flight = Flight::latest()->first();

        $response = $this->get('/flights/');

        $response->assertStatus(200);

        $response->assertSee($flight);
    }
}
