<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function create($data)
    {
        return $this->model->create($data);
    }
}