<?php

namespace Tests\Feature;

use App\Route;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RoutesTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    use DatabaseMigrations;

    /** @test */
    public function a_user_can_browse_routes()
    {
        $route = Route::latest()->first();

        $response = $this->get('/routes/');

        $response->assertStatus(200);

        $response->assertSee($route);
    }
}
