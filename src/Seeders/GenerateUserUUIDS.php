<?php

namespace Eduka\Seeders;

use Eduka\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GenerateUserUUIDS extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Generate a unique uuid per user.
        User::all()->each(function ($user) {
            $user->update(['uuid' => (string) Str::uuid()]);
        });
    }
}
