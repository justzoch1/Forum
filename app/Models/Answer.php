<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Answer extends Model
{
    use HasFactory, AsSource, Attachable, Filterable;

    protected $fillable = [
        'comment_id',
        'user_id',
        'content',
        'receiver_id'
    ];

    /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
    ];

    public function comment() {
        return $this->belongsTo(Comment::class);
    }

    public function author() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function receiver() {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
