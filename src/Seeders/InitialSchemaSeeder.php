<?php

namespace Eduka\Seeders;

use Eduka\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class InitialSchemaSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Delete all folders/files in the storage public directory.
        collect(Storage::allDirectories('public'))->each(function ($directory) {
            Storage::deleteDirectory($directory);
        });

        // Load countries + ppp data via csv file.
        $lines = file(__DIR__.'/../../database/assets/countries.csv', FILE_IGNORE_NEW_LINES);

        foreach ($lines as $line) {
            $country = explode(',', $line);

            Country::create([
                'code' => $country[1],
                'name' => str_replace(['"', '/'], ['', ''], $country[2]),

                // No ppp index ? Then should be 1.
                'ppp_index' => $country[3] == 'NULL' ? 1 : $country[3], ]);
        }
    }
}
