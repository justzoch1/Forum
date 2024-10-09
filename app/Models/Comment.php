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
}