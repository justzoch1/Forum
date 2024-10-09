<?php

namespace App\Http\Repositories;
use App\Http\Repositories\Interfaces\TopicRepositoryInterface;
use App\Models\Theme;

class TopicRepository implements TopicRepositoryInterface
{
    public function getList()
    {
        return Theme::all();
    }

}
