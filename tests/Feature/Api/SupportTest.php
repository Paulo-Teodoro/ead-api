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

        $lesson = $this->createLesson();

        $this->createSupport(50, [
            'user_id' => $user->id,
            'lesson_id' => $lesson->id
        ]);

        $this->createSupport(50, [
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
        $this->createSupport(50);
        
        $response = $this->getJson('/supports', $this->defaultHeaders());
        
        $response->assertStatus(200)
                    ->assertJsonCount(50, 'data');
    }

    public function test_get_supports_to_lesson()
    {       
        $lesson = $this->createLesson();

        $this->createSupport(20, [
            'user_id' => $this->createUser()->id,
            'lesson_id' => $lesson->id
        ]);
        $this->createSupport(50, [
            'user_id' => $this->createUser()->id,
            'lesson_id' => $this->createLesson()->id
        ]);     
    
        $payload = [
            "lesson" => $lesson->id
        ];

        $response = $this->json('GET','/supports', $payload, $this->defaultHeaders());

        $response->assertStatus(200)
                    ->assertJsonCount(20, 'data');;
    }

    public function test_get_supports_to_status()
    {       
        $this->createSupport(50,[
            'user_id' => $this->createUser()->id,
            'lesson_id' => $this->createLesson()->id,
            'status' => 'P'
        ]); 
        
        $this->createSupport(20,[
            'user_id' => $this->createUser()->id,
            'lesson_id' => $this->createLesson()->id,
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
        $this->createSupport(50,[
            'user_id' => $this->createUser()->id,
            'lesson_id' => $this->createLesson()->id
        ]); 
        
        $this->createSupport(20,[
            'user_id' => $this->createUser()->id,
            'lesson_id' => $this->createLesson()->id,
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
        $payload = [
            'lesson' => $this->createLesson()->id,
            'status' => 'B',
            'description' => 'description'
        ];                 

        $response = $this->postJson('/supports', $payload,$this->defaultHeaders());

        $response->assertStatus(422);
    }

    public function test_create_support_fail_description()
    {
        $payload = [
            'lesson' => $this->createLesson()->id,
            'status' => 'A',
            'description' => 'a'
        ];                 

        $response = $this->postJson('/supports', $payload,$this->defaultHeaders());

        $response->assertStatus(422);
    }

    public function test_create_support()
    {
        $payload = [
            'lesson' => $this->createLesson()->id,
            'status' => 'A',
            'description' => 'Correct Description'
        ];                 

        $response = $this->postJson('/supports', $payload,$this->defaultHeaders());

        $response->assertStatus(201);
    }
}
