<?php

namespace App\Repositories;

use App\Models\ReplySupport;
use App\Models\Support;
use App\Models\User;
use App\Repositories\Traits\RepositoryTrait;

class SupportRepository
{
    use RepositoryTrait;

    protected $entity;

    public function __construct(Support $model)
    {
        $this->entity = $model;
    }

    public function getSupports(array $filters = [])
    {
        return $this->entity
                    ->where(function($query) use ($filters) {
                        if(isset($filters['lesson'])) {
                            $query->where('lesson_id', $filters['lesson']);
                        }
                        
                        if(isset($filters['status'])) {
                            $query->where('status', $filters['status']);
                        }

                        if(isset($filters['filter'])) {
                            $filter = $filters['filter'];
                            $query->where('description', 'LIKE', "%{$filter}%");
                        }

                        if(isset($filters['user'])) {
                            $user = $this->getUserAuth();
                            $query->where('user_id', $user->id);
                        }
                    })
                    ->orderBy('updated_at')
                    ->get();
    }

    public function getUserSupports(array $filters = [])
    {
        $filters['user'] = true;
        return $this->getSupports($filters);
    }

    public function createNewSupport(array $data): Support
    {
        $support = $this->getUserAuth()
                        ->supports()
                        ->create([
                            'lesson_id' => $data['lesson'],
                            'status' => $data['status'],
                            'description' => $data['description']
                        ]);

        return $support;                        
    }
}