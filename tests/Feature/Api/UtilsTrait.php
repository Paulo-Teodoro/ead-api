<?php

namespace Tests\Feature\Api;

use App\Models\User;

trait UtilsTrait
{
    public function createUser()
    {
        $user = User::factory()->create();

        return $user;
    }

    public function createUserToken()
    {
        $user = $this->createUser();
        $token = $user->createToken("blah")->plainTextToken;

        return $token;
    }

    public function defaultHeaders()
    {
        return [
            'Authorization' => "Bearer {$this->createUserToken()}"
        ];
    }
}
