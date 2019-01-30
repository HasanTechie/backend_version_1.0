<?php

namespace Tests\Feature;

use App\Airline;
use App\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AirlinesTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    use DatabaseMigrations;

    /** @test */
    public function a_user_can_browse_airlines()
    {
        $airline = Airline::latest()->first();

        $response = $this->get('/airlines/');

        $response->assertStatus(200);

        $response->assertSee($airline);
    }
}
