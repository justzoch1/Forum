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
                ->join('users as comment_users', 'comment_users.id', '=', 'comments.user_id')
                ->join('themes as answer_themes', 'answer_themes.id', '=', 'comments.theme_id')
                ->select([
                    'answers.*',
                    'answer_users.name as answer_author_name',
                    'answer_users.email as answer_author_email',
                    'answers.user_id as answer_author_id',
                    'comment_users.name as comment_author_name',
                    'comment_users.email as comment_author_name',
                    'comment_users.id as comment_author_id',
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
