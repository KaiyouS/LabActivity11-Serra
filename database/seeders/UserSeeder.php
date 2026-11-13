<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Lola Serra',
            'email' => 'lola.serra@bulletin.test',
            'password' => Hash::make('bulletin2026'),
        ]);

        $kagawadUser = User::create([
            'name' => 'Kagawad Kaiyou Serra',
            'email' => 'kagawad.serra@bulletin.test',
            'password' => Hash::make('bulletin2026'),
        ]);

        $kagawadAuthor = Author::where('email', 'kagawad.serra@bulletin.test')->first();
        if ($kagawadAuthor) {
            $kagawadAuthor->update(['user_id' => $kagawadUser->id]);
        }

        User::factory()->count(8)->create();
    }
}
