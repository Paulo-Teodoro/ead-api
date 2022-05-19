<?php

namespace Tests\Feature\Api;

use App\Models\Course;
use App\Models\Module;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ModuleTest extends TestCase
{
    use UtilsTrait;

    public function test_unauthenticated()
    {
        $response = $this->getJson('/courses/fake_id/modules');

        $response->assertStatus(401);
    }

    public function test_get_all_modules_not_found()
    {
        $response = $this->getJson('/courses/fake_id/modules',$this->defaultHeaders());

        $response->assertStatus(404);
    }

    public function test_get_all_modules()
    {
        $course = $this->createCourse();

        $response = $this->getJson("/courses/{$course->id}/modules",$this->defaultHeaders());

        $response->assertStatus(200);
    }

    public function test_get_all_modules_total()
    {
        $course = $this->createCourse();
        $this->createModule(10, [
            "course_id" => $course->id
        ]);

        $response = $this->getJson("/courses/{$course->id}/modules",$this->defaultHeaders());
        $response->assertStatus(200)
                    ->assertJsonCount(10, 'data');
    }
}
