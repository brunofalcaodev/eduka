<?php

namespace Eduka\Seeders;

use Eduka\Models\Video;
use Illuminate\Database\Seeder;

class UpdateVideos2 extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Update video "Data Flow between Frontend and Backend".
        Video::firstWhere('id', 31)
             ->update([
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '466346820',
                'duration' => '14:00',
                'filename' => 'Mastering Nova - Data flow between Client and Server in an UI Tool.mp4',
             ]);
    }
}
