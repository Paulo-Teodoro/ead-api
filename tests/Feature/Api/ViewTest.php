<?php

namespace Tests\Feature\Api;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewTest extends TestCase
{
    use UtilsTrait;

    public function test_unauthenticated()
    {
        $response = $this->postJson('/lessons/viewed');

        $response->assertStatus(401);
    }

    public function test_make_viewed_error_validator()
    {
        $payload = [];

        $response = $this->postJson(
                                    '/lessons/viewed',
                                    $payload, 
                                    $this->defaultHeaders()
                                );

        $response->assertStatus(422);
    }

    public function test_make_viewed_invalid_lesson()
    {
        $payload = [
            'lesson' => 'fake_id'
        ];

        $response = $this->postJson(
                                    '/lessons/viewed',
                                    $payload, 
                                    $this->defaultHeaders()
                                );

        $response->assertStatus(422);
    }

    public function test_make_viewed_lesson()
    {
        $course = Course::factory()->create();
        $module = Module::factory()->create([
            'course_id' => $course->id
        ]);
        $lesson = Lesson::factory()->create([
            "module_id" => $module->id
        ]);

        $payload = [
            'lesson' => $lesson->id
        ];

        $response = $this->postJson(
                                    '/lessons/viewed',
                                    $payload, 
                                    $this->defaultHeaders()
                                );

        $response->assertStatus(200);
    }
}
