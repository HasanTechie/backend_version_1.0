<?php

namespace Tests\Feature;

use App\Plane;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class PlanesTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    use DatabaseMigrations;

    /** @test */
    public function a_user_can_browse_planes()
    {
        $plane = Plane::latest()->first();

        $response = $this->get('/planes/');

        $response->assertStatus(200);

        $response->assertSee($plane);
    }
}
