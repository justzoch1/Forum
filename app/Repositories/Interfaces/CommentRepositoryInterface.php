<?php

namespace App\Repositories\Interfaces;

interface CommentRepositoryInterface
{
    public function getList(): array;

    public function getListOfTopic(): array;
}
