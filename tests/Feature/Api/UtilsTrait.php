<?php

namespace Tests\Feature\Api;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Support;
use App\Models\User;

trait UtilsTrait
{
    public function createUser()
    {
        $user = User::factory()->create();

        return $user;
    }

    public function createCourse(int $quantity = 1)
    {
        $course = Course::factory()->count($quantity)->create();

        if($quantity == 1)
            return $course[0];

        return $course;
    }

    public function createModule(int $quantity = 1, array $payload = null)
    {
        $module = Module::factory()->count($quantity)->create($payload ?? ['course_id' => $this->createCourse()->id]);

        if($quantity == 1)
            return $module[0];

        return $module;
    }

    public function createLesson(int $quantity = 1, array $payload = null)
    {
        $lesson = Lesson::factory()->count($quantity)->create($payload ?? ['module_id' => $this->createModule()->id]);

        if($quantity == 1)
            return $lesson[0];

        return $lesson;
    }

    public function createSupport(int $quantity = 1, array $payload = null)
    {
        $support = Support::factory()->count($quantity)->create(
                                                            $payload ?? 
                                                            ['user_id' => $this->createUser()->id,
                                                            'lesson_id' => $this->createLesson()->id]
                                                        );

        if($quantity == 1)
            return $support[0];

        return $support;
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
