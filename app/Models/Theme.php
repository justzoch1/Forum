<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'description',
        'logo',
        'preview',
        'image'
    ];

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function scopeWithUser($query) {
        $query->join('users', 'themes.user_id', '=', 'users.id')->select('themes.*', 'users.name as user_name');
    }
}
