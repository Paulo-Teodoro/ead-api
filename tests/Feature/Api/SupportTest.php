<?php

namespace Tests\Feature\Api;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Support;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SupportTest extends TestCase
{
    use UtilsTrait;

    public function test_get_user_supports_unauthenticated()
    {
        $response = $this->getJson('/user-supports');

        $response->assertStatus(401);
    }

    public function test_get_user_supports()
    {
        $user = $this->createUser();
        $token = $user->createToken("blah")->plainTextToken;

        $course = Course::factory()->create();
        $module = Module::factory()->create([
            'course_id' => $course->id
        ]);
        $lesson = Lesson::factory()->create([
                        "module_id" => $module->id
                    ]);

        Support::factory()->count(50)->create([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id
        ]);

        Support::factory()->count(50)->create([
            'user_id' => $this->createUser()->id,
            'lesson_id' => $lesson->id
        ]);

        $response = $this->getJson('/user-supports', ['Authorization' => "Bearer {$token}"]);
        
        $response->assertStatus(200)
                ->assertJsonCount(50, 'data');
    }

    public function test_get_supports_unauthenticated()
    {
        $response = $this->getJson('/supports');
        
        $response->assertStatus(401);
    }

    public function test_get_supports()
    {
        $course = Course::factory()->create();
        $module = Module::factory()->create([
            'course_id' => $course->id
        ]);
        $lesson = Lesson::factory()->create([
                        "module_id" => $module->id
                    ]);

        Support::factory()->count(50)->create([
            'user_id' => $this->createUser()->id,
            'lesson_id' => $lesson->id
        ]);   
        
        $response = $this->getJson('/supports', $this->defaultHeaders());
        
        $response->assertStatus(200)
                    ->assertJsonCount(50, 'data');
    }

    public function test_get_supports_to_lesson()
    {
        $course = Course::factory()->create();
        $module = Module::factory()->create([
            'course_id' => $course->id
        ]);
        $lesson = Lesson::factory()->create([
                        "module_id" => $module->id
                    ]);

        $secondLesson = Lesson::factory()->create([
            "module_id" => $module->id
        ]);                    

        Support::factory()->count(50)->create([
            'user_id' => $this->createUser()->id,
            'lesson_id' => $lesson->id
        ]);

        Support::factory()->count(20)->create([
            'user_id' => $this->createUser()->id,
            'lesson_id' => $secondLesson->id
        ]);

        $payload = [
            "lesson" => $secondLesson->id
        ];

        $response = $this->json('GET','/supports', $payload, $this->defaultHeaders());

        $response->assertStatus(200)
                    ->assertJsonCount(20, 'data');;
    }

    public function test_get_supports_to_status()
    {
        $course = Course::factory()->create();
        $module = Module::factory()->create([
            'course_id' => $course->id
        ]);
        $lesson = Lesson::factory()->create([
                        "module_id" => $module->id
                    ]);                 

        Support::factory()->count(50)->create([
            'user_id' => $this->createUser()->id,
            'lesson_id' => $lesson->id,
            'status' => 'P'
        ]);

        Support::factory()->count(20)->create([
            'user_id' => $this->createUser()->id,
            'lesson_id' => $lesson->id,
            'status' => 'A'
        ]);

        $payload = [
            "status" => 'A'
        ];

        $response = $this->json('GET','/supports', $payload, $this->defaultHeaders());

        $response->assertStatus(200)
                    ->assertJsonCount(20, 'data');;
    }

    public function test_get_supports_to_description()
    {
        $course = Course::factory()->create();
        $module = Module::factory()->create([
            'course_id' => $course->id
        ]);
        $lesson = Lesson::factory()->create([
                        "module_id" => $module->id
                    ]);                 

        Support::factory()->count(50)->create([
            'user_id' => $this->createUser()->id,
            'lesson_id' => $lesson->id
        ]);

        Support::factory()->count(20)->create([
            'user_id' => $this->createUser()->id,
            'lesson_id' => $lesson->id,
            'description' => 'randomWord'
        ]);

        $payload = [
            "filter" => 'randomWord'
        ];

        $response = $this->json('GET','/supports', $payload, $this->defaultHeaders());

        $response->assertStatus(200)
                    ->assertJsonCount(20, 'data');;
    }

    public function test_create_support_unauthenticated()
    {
        $response = $this->postJson('/supports');

        $response->assertStatus(401);
    }

    public function test_create_support_error_validator()
    {
        $response = $this->postJson('/supports', [],$this->defaultHeaders());

        $response->assertStatus(422);
    }

    public function test_create_support_fail_lesson()
    {
        $payload = [
            'lesson' => '1',
            'status' => 'A',
            'description' => 'description'
        ];                 

        $response = $this->postJson('/supports', $payload,$this->defaultHeaders());

        $response->assertStatus(422);
    }

    public function test_create_support_fail_status()
    {
        $course = Course::factory()->create();
        $module = Module::factory()->create([
            'course_id' => $course->id
        ]);
        $lesson = Lesson::factory()->create([
                        "module_id" => $module->id
                    ]);

        $payload = [
            'lesson' => $lesson->id,
            'status' => 'B',
            'description' => 'description'
        ];                 

        $response = $this->postJson('/supports', $payload,$this->defaultHeaders());

        $response->assertStatus(422);
    }

    public function test_create_support_fail_description()
    {
        $course = Course::factory()->create();
        $module = Module::factory()->create([
            'course_id' => $course->id
        ]);
        $lesson = Lesson::factory()->create([
                        "module_id" => $module->id
                    ]);

        $payload = [
            'lesson' => $lesson->id,
            'status' => 'A',
            'description' => 'a'
        ];                 

        $response = $this->postJson('/supports', $payload,$this->defaultHeaders());

        $response->assertStatus(422);
    }

    public function test_create_support()
    {
        $course = Course::factory()->create();
        $module = Module::factory()->create([
            'course_id' => $course->id
        ]);
        $lesson = Lesson::factory()->create([
                        "module_id" => $module->id
                    ]);

        $payload = [
            'lesson' => $lesson->id,
            'status' => 'A',
            'description' => 'Correct Description'
        ];                 

        $response = $this->postJson('/supports', $payload,$this->defaultHeaders());

        $response->assertStatus(201);
    }
}
