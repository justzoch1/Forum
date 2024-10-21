<?php

namespace App\Services;

use App\Models\Theme;
use Illuminate\Support\Facades\Log;

class IndexControllerService
{
    public function search(?string $q = '')
    {
        $query = Theme::query();

        if (!empty($q)) {
            $query->where(function ($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%')
                    ->orWhere('description', 'like', '%' . $q . '%');
            });
        }

        $themes = $query->withCount('comments')
            ->orderBy('comments_count', 'desc')->paginate(8);

        Log::info($themes);

        return $themes;
    }
}
