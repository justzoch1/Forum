<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'status',
        'theme_id',
        'user_id'
    ];

    public function answers() {
        return $this->hasMany(Answer::class);
    }

    public function theme() {
        return $this->belongsTo(Theme::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function scopeWithThemeAndUser($query) {
        $query->join('themes', 'themes.id', '=', 'comments.theme_id')
            ->join('users', 'users.id', '=', 'comments.user_id')
            ->select(['comments.*', 'themes.name as theme_name', 'users.name as user_name', 'users.email as user_email']);
    }

    public function scopeWithAnswers($query)
    {
        $query->with(['answers' => function ($query) {
            $query->join('comments', 'comments.id', '=', 'answers.comment_id')
                ->join('users as answer_users', 'answer_users.id', '=', 'answers.user_id')
                ->join('users as receivers', 'receivers.id', '=', 'answers.receiver_id')
                ->select([
                    'answers.*',
                    'answer_users.name as author_name',
                    'answer_users.email as author_email',
                    'answers.user_id as author_id',
                    'receivers.name as receiver_name',
                    'receivers.email as receiver_email',
                    'receivers.id as receiver_id',
                ])
                ->orderBy('created_at', 'asc');
        }]);
    }

    public function scopeOnlyApproved($query) {
        $query->where('status', 'approved');
    }

    public function scopeSortByAnswerCount($query) {
        $query->withCount('answers')
            ->orderBy('answers_count', 'desc');
    }
}
