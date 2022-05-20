<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\Module;

class ModuleRepository
{
    protected $entity;

    public function __construct(Module $model)
    {
        $this->entity = $model;
    }

    public function getModulesByCourseId(string $courseId)
    {
        $course = Course::findOrFail($courseId);
        
        return $this->entity
                    ->where('course_id', $courseId)
                    ->with('lessons.views')
                    ->get();
    }
}