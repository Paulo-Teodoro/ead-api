<?php

namespace Tests\Feature\Api;

use App\Models\User;

trait UtilsTrait
{
    public function createUserToken()
    {
        $user = User::factory()->create();
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
