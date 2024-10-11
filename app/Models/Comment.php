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
                ->join('users', 'users.id', '=', 'answers.user_id')
                ->join('themes as answer_themes', 'answer_themes.id', '=', 'comments.theme_id')
                ->select(['answers.*', 'users.name as user_name', 'answer_themes.name as theme_name', 'users.email as user_email', 'comments.content as content', 'comments.last_updated as last_updated']);
        }]);
    }

    public function scopeOnlyApproved($query) {
        $query->where('status', 'approved');
    }
}
