<?php

namespace Tests\Feature\Api;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Support;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReplySupportTest extends TestCase
{
    use UtilsTrait;

    public function test_create_reply_support_unauthenticated()
    {
        $response = $this->postJson('/replies');

        $response->assertStatus(401);
    }

    public function test_create_reply_support_error_validator()
    {
        $response = $this->postJson('/replies', [],$this->defaultHeaders());

        $response->assertStatus(422);
    }

    public function test_create_reply_support_fail_support()
    {
        $payload = [
            'support_id' => '1',
            'description' => 'description'
        ];                 

        $response = $this->postJson('/replies', $payload,$this->defaultHeaders());

        $response->assertStatus(422);
    }

    public function test_create_reply_support_fail_description()
    {
        $course = Course::factory()->create();
        $module = Module::factory()->create([
            'course_id' => $course->id
        ]);
        $lesson = Lesson::factory()->create([
                        "module_id" => $module->id
                    ]);

        $support = Support::factory()->create([
            'lesson_id' => $lesson->id,
            'user_id' => $this->createUser()->id
        ]);              

        $payload = [
            'support_id' => $support->id,
            'description' => 'd'
        ];                      

        $response = $this->postJson('/replies', $payload,$this->defaultHeaders());

        $response->assertStatus(422);
    }

    public function test_create_reply_support()
    {
        $course = Course::factory()->create();
        $module = Module::factory()->create([
            'course_id' => $course->id
        ]);
        $lesson = Lesson::factory()->create([
                        "module_id" => $module->id
                    ]);

        $support = Support::factory()->create([
            'lesson_id' => $lesson->id,
            'user_id' => $this->createUser()->id
        ]);              

        $payload = [
            'support_id' => $support->id,
            'description' => 'description'
        ];                 

        $response = $this->postJson('/replies', $payload,$this->defaultHeaders());

        $response->assertStatus(201);
    }
}
