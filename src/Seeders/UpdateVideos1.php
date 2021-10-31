<?php

namespace Eduka\Seeders;

use Eduka\Models\Chapter;
use Eduka\Models\Video;
use Illuminate\Database\Seeder;

class UpdateVideos1 extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Update video "What is an UI component?".
        Video::firstWhere('id', 30)
             ->update([
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '465169834',
                'duration' => '05:38',
                'filename' => 'Mastering Nova - What is an UI Component.mp4',
             ]);

        // Change Video scope of the "Designing a simple UI Component".
        Video::firstWhere('id', 31)
             ->update([
                'title' => 'Data flow between Client and Server in an UI Tool',
                'details' => 'Lets evolve a Tool to show an example how to transport data between your frontend to the server, and return transformed data into your frontend again',
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '466346820',
                'duration' => '14:00',
                'filename' => 'Mastering Nova - Data flow between Client and Server in an UI Tool.mp4',
             ]);

        Video::firstWhere('id', 31)
              ->addMedia(__DIR__.'/../../resources/images/videos/data-flow-between-client-server-client.jpg')
              ->preservingOriginal()
              ->toMediaCollection();

        // Uploaded to Vimeo.
        Video::createWithImage(
            ['title' => 'Integrating OAuth authentication with Laravel Socialite',
             'details' => 'Lets change the login workflow to include OAuth authentication with 3rd party authentication providers like Github or Twitter',
             'is_visible' => true,
             'is_active' => true,
             'vimeo_id' => '496489215',
             'duration' => '06:32',
             'chapter_id' => Chapter::firstWhere('title', 'Deep Dive on UI Components')->id,
            ],
            'integrating-socialite-for-oauth-authentication.jpg'
        );

        // Uploaded to Vimeo.
        Video::createWithImage(
            ['title' => 'Spatie Multi-Tenancy',
             'details' => 'Spatie have a package about how to use multitenancy in your web apps. Lets learn how to use it inside Nova, having a way to connect to the respective tenant database accordingly to the tenant subdomain',
             'is_visible' => true,
             'is_active' => true,
             'vimeo_id' => '497772054',
             'duration' => '09:55',
             'chapter_id' => Chapter::firstWhere('title', 'Best community Packages')->id,
            ],
            'package-spatie-laravel-multitenancy.jpg'
        );
    }
}
