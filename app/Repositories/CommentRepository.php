<?php

namespace App\Repositories;

use App\Models\Comment;

class CommentRepository extends BaseRepository
{

    public function setModel(): string
    {
        // TODO: Implement setModel() method.
        return Comment::class;
    }
}
