<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Answer;
use App\Models\Message;
use App\Models\Theme;
use App\Models\Comment;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         User::factory(100)->create();
         Message::factory(500)->create();
         Theme::factory(20)->create();
         Comment::factory(500)->create();
         Answer::factory(1000)->create();
    }
}
