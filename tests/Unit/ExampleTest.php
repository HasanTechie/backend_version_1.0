<?php

namespace Tests\Unit;

use App\User;
use App\Plane;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class ExampleTest extends TestCase
{
    use DatabaseMigrations;

    public function testApplication()
    {
        $user = factory(User::class)->create();

        $plane = Plane::latest()->first();

        $response =
            $this->actingAs($user)
                ->get('/planes')
                ->assertSeeText('Total number of planes')
                ->assertStatus(200)
                ->assertSee($plane); //i
    }

}
