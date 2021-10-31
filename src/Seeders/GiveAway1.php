<?php

namespace Eduka\Seeders;

use Eduka\Models\Website;
use Illuminate\Database\Seeder;

class GiveAway1 extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Website::where('id', 1)->update([
            'giveaway_starts_at' => '2020-10-19 07:00:00',
            'giveaway_ends_at' => '2020-10-24 00:00:00', ]);
    }
}
