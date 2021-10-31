<?php

namespace Eduka\Seeders;

use Eduka\Models\Video;
use Illuminate\Database\Seeder;

class UpdateVideosForStorageSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Update video with file links to Backblaze.
        Video::firstWhere('id', 1)
             ->update(['filename' => 'Mastering Nova - Installing Nova.mp4']);

        Video::firstWhere('id', 2)
             ->update(['filename' => 'Mastering Nova - First look at the file structure.mp4']);

        Video::firstWhere('id', 3)
             ->update(['filename' => 'Mastering Nova - What is a Resource.mp4']);

        Video::firstWhere('id', 4)
             ->update(['filename' => 'Mastering Nova - Creating your first Resource.mp4']);

        Video::firstWhere('id', 5)
             ->update(['filename' => 'Mastering Nova - First glance to the Resource Fields.mp4']);

        Video::firstWhere('id', 6)
             ->update(['filename' => 'Mastering Nova - The beauty of Filters.mp4']);

        Video::firstWhere('id', 7)
             ->update(['filename' => 'Mastering Nova - Getting deeper on data viewing using Lenses.mp4']);

        Video::firstWhere('id', 8)
             ->update(['filename' => 'Mastering Nova - Executing Actions on your Resources.mp4']);

        Video::firstWhere('id', 9)
             ->update(['filename' => 'Mastering Nova - Visualizing data using Metrics.mp4']);

        Video::firstWhere('id', 10)
             ->update(['filename' => 'Mastering Nova - First glance at Resource Relationships.mp4']);

        Video::firstWhere('id', 11)
             ->update(['filename' => 'Mastering Nova - Using customized accessors in the global search.mp4']);

        Video::firstWhere('id', 12)
             ->update(['filename' => 'Mastering Nova - Recompiling Nova assets.mp4']);

        Video::firstWhere('id', 13)
             ->update(['filename' => 'Mastering Nova - Data sync between server and client UI components.mp4']);

        Video::firstWhere('id', 14)
             ->update(['filename' => 'Mastering Nova - Creating your first UI Component - An enhanced Field.mp4']);

        Video::firstWhere('id', 15)
             ->update(['filename' => 'Mastering Nova - Sorting your Resources in the Sidebar.mp4']);

        Video::firstWhere('id', 16)
             ->update(['filename' => 'Mastering Nova - The power of abstract Resources.mp4']);

        Video::firstWhere('id', 17)
             ->update(['filename' => 'Mastering Nova - Loading Resources from custom locations.mp4']);

        Video::firstWhere('id', 18)
             ->update(['filename' => 'Mastering Nova - Creating a Filter to select columns for your Index View.mp4']);

        Video::firstWhere('id', 19)
             ->update(['filename' => 'Mastering Nova - The full power of Resource Policies.mp4']);

        Video::firstWhere('id', 20)
             ->update(['filename' => 'Mastering Nova - Single package creation for all of your UI Components.mp4']);

        Video::firstWhere('id', 21)
             ->update(['filename' => 'Mastering Nova - Customizing your Resource visibility.mp4']);

        Video::firstWhere('id', 22)
             ->update(['filename' => 'Mastering Nova - How to correctly use the Index Query.mp4']);

        Video::firstWhere('id', 23)
             ->update(['filename' => 'Mastering Nova - Using Resource data scopes.mp4']);

        Video::firstWhere('id', 24)
             ->update(['filename' => 'Mastering Nova - Cloning Resources for a better Resource categories strategy.mp4']);

        Video::firstWhere('id', 25)
             ->update(['filename' => 'Mastering Nova - Polymorphic Relationships.mp4']);

        Video::firstWhere('id', 26)
             ->update(['filename' => 'Mastering Nova - Many-to-Many Relationships with additional pivot columns.mp4']);

        Video::firstWhere('id', 27)
             ->update(['filename' => 'Mastering Nova - Changing Stub Files.mp4']);

        Video::firstWhere('id', 28)
             ->update(['filename' => 'Mastering Nova - Configuring field groups for each display context.mp4']);

        Video::firstWhere('id', 29)
             ->update(['filename' => 'Mastering Nova - Resource 1-o-1 Checklist Guidelines.mp4']);

        Video::firstWhere('id', 45)
             ->update(['filename' => 'Mastering Nova - Search Relations.mp4']);
    }
}
