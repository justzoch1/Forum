<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Answer;
use App\Models\Message;
use App\Models\Theme;
use App\Models\Comment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//        User::create([
//            'id' => 0,
//            'name' => 'Anonim',
//            'email' => 'anonim@example.org',
//            'email_verified_at' => now(),
//            'password' => 'password',
//            'remember_token' => Str::random(10),
//        ]);

         User::factory(12)->create();
         Message::factory(500)->create();
         Theme::factory(20)->create();
         Comment::factory(200)->create();
         Answer::factory(500)->create();
    }
}
