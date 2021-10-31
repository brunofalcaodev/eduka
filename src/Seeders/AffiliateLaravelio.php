<?php

namespace Eduka\Seeders;

use Eduka\Models\Affiliate;
use Illuminate\Database\Seeder;

class AffiliateLaravelio extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Affiliate::create([
            'domain' => 'laravel.io',
            'commission' => 35,
            'paddle_vendor_id' => '12345',
        ]);
    }
}
