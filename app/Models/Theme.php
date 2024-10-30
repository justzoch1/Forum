<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Theme extends Model
{
    use HasFactory, AsSource, Attachable, Filterable;

    protected $fillable = [
        'name',
        'user_id',
        'description',
        'preview',
    ];

    /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
    ];

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function scopeWithUser($query) {
        $query->join('users', 'themes.user_id', '=', 'users.id')->select('themes.*', 'users.name as user_name');
    }
    public function scopeApprovedCommentsCount(): int
    {
        return $this->comments()->where('status', 'approved')->count();
    }
}
