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
        $payload = [
            'support_id' => $this->createSupport()->id,
            'description' => 'd'
        ];                      

        $response = $this->postJson('/replies', $payload,$this->defaultHeaders());

        $response->assertStatus(422);
    }

    public function test_create_reply_support()
    {       
        $payload = [
            'support_id' => $this->createSupport()->id,
            'description' => 'description'
        ];                 

        $response = $this->postJson('/replies', $payload,$this->defaultHeaders());

        $response->assertStatus(201);
    }
}
