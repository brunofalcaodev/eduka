<?php

namespace Eduka\Seeders;

use Eduka\Models\Chapter;
use Eduka\Models\Country;
use Eduka\Models\Link;
use Eduka\Models\PaddleLog;
use Eduka\Models\Subscriber;
use Eduka\Models\User;
use Eduka\Models\Video;
use Eduka\Models\Website;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class InitialSeeder extends Seeder
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

        // Seed website.
        $website = Website::create(['title' => 'Mastering Nova - Laravel Nova tutorial']);

        $website->addMedia(__DIR__.'/../../resources/images/website/header-1.jpg')
                ->preservingOriginal()
                ->toMediaCollection();

        $website->addMedia(__DIR__.'/../../resources/images/website/header-2.jpg')
                ->preservingOriginal()
                ->toMediaCollection();

        $website->addMedia(__DIR__.'/../../resources/images/website/header-3.jpg')
                ->preservingOriginal()
                ->toMediaCollection();

        $website->addMedia(__DIR__.'/../../resources/images/website/social-card.jpg')
                ->preservingOriginal()
                ->toMediaCollection('social');

        // Load countries + ppp data via csv file.
        $lines = file(__DIR__.'/../../database/seeds/countries.csv', FILE_IGNORE_NEW_LINES);

        foreach ($lines as $line) {
            $country = explode(',', $line);

            Country::create([
                'code' => $country[1],
                'name' => str_replace(['"', '/'], ['', ''], $country[2]),

                // No ppp index ? Then should be 1.
                'ppp_index' => $country[3] == 'NULL' ? 1 : $country[3], ]);
        }

        // Create the only user that can have access to nova.
        User::create([
            'name' => 'Bruno Falcao',
            'email' => 'bruno@masteringnova.com',
            'password' => bcrypt('password'),
        ]);

        // Create all chapters.
        $chapter = Chapter::create(
            ['title' => 'Nova Fundamentals']
        );

        $chapter->addMedia(__DIR__.'/../../resources/images/chapters/the-fundamentals-social-card.jpg')
                ->preservingOriginal()
                ->toMediaCollection('social');

        $chapter->addMedia(__DIR__.'/../../resources/images/chapters/the-fundamentals-featured.jpg')
                ->preservingOriginal()
                ->toMediaCollection();

        // Uploaded to Vimeo.
        $chapter->videos()->save(
            $this->video([
                'title' => 'Installing Nova',
                'details' => "Let's learn how to install Nova using the direct local folder repository or via composer update. Also, why you should use symlinks to sync your files and not mirror them into the vendor directory",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '426578439',
                'duration' => '09:46',
                'filename' => 'Mastering Nova - Installing Nova.mp4',
            ], 'installing-nova.jpg')
        );

        // Create respective video links.
        $videoId = Video::latest()->first()->id;
        $link = Link::create(['title' => 'Laravel Nova Docs reference',
                              'url' => 'https://nova.laravel.com/docs/3.0/installation.html#installing-nova',
                              'video_id' => $videoId, ]);

        $link = Link::create(['title' => 'Youtube video',
                              'url' => 'https://www.youtube.com/watch?v=52jZ4Foh1EY',
                              'video_id' => $videoId, ]);

        // Uploaded to Vimeo.
        $chapter->videos()->save(
            $this->video([
                'title' => 'First glance at the file structure',
                'details' => "After installing Nova, let's look at what have changed in your web app folders. What resources were installed and how you can use them to customize your frontend view partials, a walkthrough the nova.php configuration file, the nova source files and how Nova communicates your user actions via the nova-api",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '440095119',
                'duration' => '11:49',
                'filename' => 'Mastering Nova - First look at the file structure.mp4',
            ], 'first-glance-at-the-file-structure.jpg')
        );

        // Uploaded to Vimeo.
        $chapter->videos()->save(
            $this->video([
                'title' => 'What is a Resource?',
                'details' => "Let's take a look to the Resource that comes by default when you install your Nova instance, that is the Users Resource. You'll learn the default basic properties and also see what happens when you change them. And after this lesson we will create our first Resource and start working from there",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '440130924',
                'duration' => '09:03',
                'filename' => 'Mastering Nova - What is a Resource.mp4',
            ], 'what-is-a-resource.jpg')
        );

        // Uploaded to Vimeo.
        $chapter->videos()->save(
            $this->video([
                'title' => 'Creating your first Resource',
                'details' => "After using a migration to create our Products table, and the respective Eloquent Model, time to create our first Resource and start working from there. You'll learn how to use the Resource default properties, plus some that will very useful, and also how to create the fields for your Resource. We will then do a first try in creating the Fields and prepare to deep dive a little further in this scope",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '440233416',
                'duration' => '15:38',
                'filename' => 'Mastering Nova - Creating your first Resource.mp4',
            ], 'creating-your-first-resource.jpg')
        );

        // Uploaded to Vimeo.
        $chapter->videos()->save(
            $this->video([
                'title' => 'First glance to the Resource Fields',
                'details' => 'Fields are one of the most important attributes of your Resource. There is tons of things you can configure them, validations, visibilty, display computation, besides extending them with your own business logic. In this tutorial we will pay a visit to the most popular Field methods, like visibility and validation',
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '440237994',
                'duration' => '09:18',
                'filename' => 'Mastering Nova - First glance to the Resource Fields.mp4',
            ], 'first-glance-to-the-resource-fields.jpg')
        );

        // Uploaded to Vimeo.
        $chapter->videos()->save(
            $this->video([
                'title' => 'The beauty of Filters',
                'details' => "Lets start interacting with other functionalities of Nova, in this case the Filters. Let's create a first Filter, and apply it to 2 different Resources for filter active statuses. Also, you will learn how to dynamically render the header of the filter given the resource singular name that you working with",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '440406246',
                'duration' => '13:12',
                'filename' => 'Mastering Nova - The beauty of Filters.mp4',
            ], 'the-beauty-of-filters.jpg')
        );

        // Uploaded on Vimeo.
        $chapter->videos()->save(
            $this->video([
                'title' => 'Getting deeper on data viewing using Lenses',
                'details' => "Lenses allows you to completely redesign your index query in order to show the data in a customized way. So, in this lesson we will create a Lens about the Top Buyers from our users and Products. We will also learn how to be able to sort computed columns since normally you can't do that without transforming your query in a different way",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '440457414',
                'duration' => '17:17',
                'filename' => 'Mastering Nova - Getting deeper on data viewing using Lenses.mp4',
            ], 'getting-deeper-on-data-viewing-using-lenses.jpg')
        );

        // Uploaded to Vimeo.
        $chapter->videos()->save(
            $this->video([
                'title' => 'Executing Actions on your Resources',
                'details' => "Actions allows you to bulk execute operations in the Resources that you select. So, let's create a first action that will change the status value in the Users Resources that we select, and also we will learn how to run Actions directly in the rows and not only in the Resources that we check",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '440566590',
                'duration' => '11:52',
                'filename' => 'Mastering Nova - Executing Actions on your Resources.mp4',
            ], 'executing-actions-on-your-resources.jpg')
        );

        // Uploaded to Vimeo.
        $chapter->videos()->save(
            $this->video([
                'title' => 'Visualizing data using Metrics',
                'details' => "Metrics are charts that allows you to quickly display data from your Resources. In this tutorial let's go by the 3 types of metrics you can create and see how easy they are to customize",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '440572988',
                'duration' => '13:01',
                'filename' => 'Mastering Nova - Visualizing data using Metrics.mp4',
            ], 'visualizing-data-using-metrics.jpg')
        );

        // Uploaded to Vimeo.
        $chapter->videos()->save(
            $this->video([
                'title' => 'First glance at Resource Relationships',
                'details' => "Relationships are the heart of Laravel Nova. It's the way you connect resources with other resources by using the Eloqunet relationships that were defined in your Eloquent models. In this tutorial let's pass by the most common relationships and also see in detail the Polymorphic 1-to-Many relationships, leaving the Polymorphic Many-to-Many and the 1-to-Many with pivot editing for a later video",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '440590153',
                'duration' => '23:30',
                'filename' => 'Mastering Nova - First glance at Resource Relationships.mp4',
            ], 'first-glance-at-resource-relationships.jpg')
        );

        // Uploaded to Vimeo.
        $chapter->videos()->save(
            $this->video([
                'title' => 'Using customized accessors on Global Search',
                'details' => "What if you need to have results in your Global Search but not given by by a native eloquent attribute but by a computer attribute (Accessor) that you will create? Let's deep dive in a way to completely generate computed columns with the results you want to show in your Global Search results",
                'is_visible' => true,
                'is_active' => true,
                'is_free' => true,
                'vimeo_id' => '417004687',
                'duration' => '05:57',
                'filename' => 'Mastering Nova - Using customized accessors in the global search.mp4',
            ], 'using-customized-accessors-on-global-search.jpg')
        );

        // Uploaded to Vimeo.
        $chapter->videos()->save(
            $this->video([
                'title' => 'Recompiling Nova assets',
                'details' => 'Nova comes with assets that you can change, and recompile to fit your needs. In this tutorial we will learn how to recompile the Nova assets and publish them in your public directory. At the end we will also correct the field TextArea to become aligned with width, so we can correct the Vue component, recompile, publish and see it corrected in your forms',
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '440640368',
                'duration' => '10:04',
                'filename' => 'Mastering Nova - Recompiling Nova assets.mp4',
            ], 'recompiling-nova-assets.jpg')
        );

        // Uploaded to Vimeo.
        $chapter->videos()->save(
            $this->video([
                'title' => 'Data sync between server and client UI components',
                'details' => 'We have server and client components, life for instance a Field. There is a server configuration, and a Vue Field component. In this tutorial we we learn how to pass data from the server to the vue component so you can then use it to affect the field itself. For instance, in this case, to pass a RGB color from the server to change the background color of the Text Field',
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '440652958',
                'duration' => '05:47',
                'filename' => 'Mastering Nova - Data sync between server and client UI components.mp4',
            ], 'data-sync-between-server-and-client-ui-components.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Creating your first UI Component - An enhanced Field',
                'details' => 'After learning how to pass data to default Nova UI components, time to create our first UI Component, a Field. We will then develop it to be able to receive an icon parameter from the server, to render a fontawesome icon. Pretty neat!',
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '440670577',
                'duration' => '15:15',
                'filename' => 'Mastering Nova - Creating your first UI Component - An enhanced Field.mp4',
            ], 'creating-your-first-ui-component-enhanced-field.jpg')
        );

        // Create all chapters.
        $chapter = Chapter::create(
            ['title' => 'Deep Dive on Resources']
        );

        $chapter->addMedia(__DIR__.'/../../resources/images/chapters/deep-dive-on-resources-social-card.jpg')
                ->preservingOriginal()
                ->toMediaCollection('social');

        $chapter->addMedia(__DIR__.'/../../resources/images/chapters/deep-dive-on-resources-featured.jpg')
                ->preservingOriginal()
                ->toMediaCollection();

        $chapter->videos()->save(
            $this->video([
                'title' => 'Sorting your Resources in the Sidebar',
                'details' => 'There is an undocumented feature that allows you to sort your Resources on your Sidebar, using a priority attribute. Lets find out how to use it correctly',
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '440796216',
                'duration' => '03:55',
                'filename' => 'Mastering Nova - Sorting your Resources in the Sidebar.mp4',
            ], 'sorting-your-resources-in-the-sidebar.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'The power of abstract Resources',
                'details' => "We'll start by learning how we can use Abstract Resources to contextualize specific common features like Actions, Lenses, into a group of Resources, and also how to use a master abstract Resource that will able us to use default sorting outside of the ID field",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '440802260',
                'duration' => '21:41',
                'filename' => 'Mastering Nova - The power of abstract Resources.mp4',
            ], 'the-power-of-abstract-resources.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Loading custom location Resources',
                'details' => "By default, Nova stores your Resources in the Eduka\Nova namespace. But there is a way to load your Resources from another namespaces. Lets find out how.",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '440813817',
                'duration' => '02:19',
                'filename' => 'Mastering Nova - Loading Resources from custom locations.mp4',
            ], 'loading-resources-from-custom-locations.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Creating a filter to select columns for your Index view',
                'details' => 'Getting deeper in Abstract Resources, lets create a Filter that will dynamically use your Resource columns to be visible or not in your index View',
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '443540783',
                'duration' => '21:36',
                'filename' => 'Mastering Nova - Creating a Filter to select columns for your Index View.mp4',
            ], 'filter-to-select-columns.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'The full power of Resource Policies',
                'details' => "One of the most flexible authorization models on Nova is to use Resource Policies to restrict specific data scopes. Let's see how to restrict certain activities via Policy configurations",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '444352691',
                'duration' => '09:21',
                'filename' => 'Mastering Nova - The full power of Resource Policies.mp4',
            ], 'the-full-power-of-resource-policies.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Single package creation for all of your UI Components',
                'details' => 'Normally you need to create a new composer package for each any extension UI component. This lesson we will learn how to keep things tidy and just have a single package for everything',
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '446299838',
                'duration' => '13:59',
                'filename' => 'Mastering Nova - Single package creation for all of your UI Components.mp4',
            ], 'single-package-creation-for-all-of-your-ui-components.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Customizing your Resource visibility',
                'details' => 'There will be cases where you want to have a Resource not visible in the Sidebar, but still use them in the Relationships. In this Lesson we will learn how to use them and also see how to authorize them to be displayable or not',
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '458751323',
                'duration' => '04:03',
                'filename' => 'Mastering Nova - Customizing your Resource visibility.mp4',
            ], 'customizing-your-resource-visibility.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'How to correctly use the Index Query',
                'details' => "If you ever need to filter data given your user permission, you shouldn't use index queries. For that, we will dynamically attach model local scopes. Let's find out how",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '462826941',
                'duration' => '05:02',
                'filename' => 'Mastering Nova - How to correctly use the Index Query.mp4',
            ], 'how-to-correctly-use-the-index-query.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Using Resource data scopes',
                'details' => 'One of the critical features you might have is to only show the data that your current user needs/should be able to see (and interact). Lets use Model scopes to limit the data we make accessible to the Nova logged user',
                'is_visible' => true,
                'is_active' => true,
                'is_free' => true,
                'vimeo_id' => '462968160',
                'duration' => '04:36',
                'filename' => 'Mastering Nova - Using Resource data scopes.mp4',
            ], 'using-resource-data-scopes.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Cloning Resources for a better Resource categories strategy',
                'details' => 'If you have to show Resources that will need to categorize data, you might need to look at a Resource/Model cloning approach. In this lesson we will learn how to use a package to close our Model, and to attach it with a specific scope, as a possible approach',
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '463216529',
                'duration' => '09:46',
                'filename' => 'Mastering Nova - Cloning Resources for a better Resource categories strategy.mp4',
            ], 'cloning-resources-for-a-better-resource-strategy.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Polymorphic Relationships',
                'details' => "Let's create a more advanced relationship that is the polymorphic relationship (MorphTo) and see how we can use it with our Resources",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '463229822',
                'duration' => '06:48',
                'filename' => 'Mastering Nova - Polymorphic Relationships.mp4',
            ], 'polymorphic-relationships.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Many-to-Many Relationships with additional pivot columns',
                'details' => 'Another complex relationship, but with the fact that you will also have access to the additional fields in your pivot table, and also how to use it with custom pivot names',
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '463255054',
                'duration' => '03:22',
                'filename' => 'Mastering Nova - Many-to-Many Relationships with additional pivot columns.mp4',
            ], 'many-to-many-relationship-with-additional-pivot-columns.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Changing Stub Files',
                'details' => "You can export the default stub files and change them. Let's get an example on one of them and see how you can further use it",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '463822872',
                'duration' => '02:28',
                'filename' => 'Mastering Nova - Changing Stub Files.mp4',
            ], 'changing-stub-files.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Configuring field groups for each display context',
                'details' => "On Nova 3.1.0 you have the ability to change the behavior of fields based on their visibility context (index, form, detail). Let's see a pratical example on how to change a field data based on these display contexts",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '463832522',
                'duration' => '02:23',
                'filename' => 'Mastering Nova - Configuring field groups for each display context.mp4',
            ], 'configuring-field-groups-for-each-display-context.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Resource 1-o-1 Checklist Guidelines',
                'details' => 'Time to wrap up a bit and create what I call a Resource checklist! Meaning, how to do you follow a sequence of actions and validations to guarantee that you have covered it all when creating your Resources',
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '463966964',
                'duration' => '15:48',
                'filename' => 'Mastering Nova - Resource 1-o-1 Checklist Guidelines.mp4',
            ], 'resource-1-o-1-checklist-guidelines.jpg')
        );

        $chapter = Chapter::create(
            ['title' => 'Deep Dive on UI Components']
        );

        $chapter->addMedia(__DIR__.'/../../resources/images/chapters/deep-dive-on-ui-components-social-card.jpg')
                ->preservingOriginal()
                ->toMediaCollection('social');

        $chapter->addMedia(__DIR__.'/../../resources/images/chapters/deep-dive-on-ui-components-featured.jpg')
                ->preservingOriginal()
                ->toMediaCollection();

        $chapter->videos()->save(
            $this->video([
                'title' => 'What is an UI Component?',
                'details' => "Anything that you normally extend on Nova is an UI component: A Field, Card, Lenses, Filter, Metric or a Tool. Let's see the structure and start understanding how Nova interacts with custom UI Components",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '465169834',
                'duration' => '05:38',
                'filename' => 'Mastering Nova - What is an UI Component.mp4',
            ], 'what-is-an-ui-component.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Data flow between Client and Server in an UI Tool',
                'details' => 'Lets evolve a Tool to show an example how to transport data between your frontend to the server, and return transformed data into your frontend again',
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '466346820',
                'duration' => '14:00',
                'filename' => 'Mastering Nova - Data flow between Client and Server in an UI Tool.mp4',
            ], 'data-flow-between-client-server-client.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'UI Component properties you can use',
                'details' => "What Vue properties do you have that are given by Nova? Let's explore these properties and take the best of them",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '466816763',
                'duration' => '14:56',
                'filename' => 'Mastering Nova - UI Component properties you can use.mp4',
            ], 'ui-component-properties-you-can-use.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Reusing Nova UI Components',
                'details' => "You want to create your UI component, but what if you want already to use a base one from Nova? Let's learn how to not re-invent the wheel",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '466950177',
                'duration' => '11:51',
                'filename' => 'Mastering Nova - Reusing default Nova UI Components.mp4',
            ], 'reusing-nova-ui-components.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Using $emit events',
                'details' => 'A Nova way to transfer data between frontend UI components is to use the emit event. It will broadcast an event, for others to then trigger further actions',
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '467171017',
                'duration' => '10:12',
                'filename' => 'Mastering Nova - Using $emit events.mp4',
            ], 'using-emit-events.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Pratical example with $emit on 2 Dropdowns',
                'details' => "Let's see the \$emit event in practise working between 2 dropdowns. One dropdown will affect the value change of another dropdown",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '467171017',
                'duration' => '10:12',
                'filename' => 'Mastering Nova - Using $emit events.mp4',
            ], 'practical-example-2-dropdowns.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => "Let's build a Tool! Helpdesk System",
                'details' => "Let's create a ticketing system together! Starting by creating a Theme and a Tool",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '472802328',
                'duration' => '14:43',
                'filename' => 'Mastering Nova - Lets build a Tool - Helpdesk System.mp4',
            ], 'creating-the-composer-package.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Managing Ticket permissions and authorizations',
                'details' => 'Lets continue to create the Tool but now implementing the correct authorization and operator permissions, and see impacts of when to use the right approach or not',
                'is_visible' => true,
                'is_active' => true,
                'is_free' => true,
                'vimeo_id' => '473656340',
                'duration' => '19:14',
                'filename' => 'Mastering Nova - Managing Tickets Permissions and Authorizations.mp4',
            ], 'managing-ticket-permissions-and-authorizations.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Creating a quick My Tickets / Unassigned Filter',
                'details' => 'Lets create a quick filter just to have the Operator having filters his tickets by the ones assigned to himself or unassigned ones',
                'is_visible' => true,
                'is_active' => true,
                'is_free' => true,
                'vimeo_id' => '473656340',
                'duration' => '19:14',
                'filename' => 'Mastering Nova - Creating a quick My Tickets / Unassigned Filter.mp4',
            ], 'creating-a-quick-my-tickets-filter.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Creating the Assign Ticket to Myself action',
                'details' => 'After having our All Tickets Resource index, we need to create an action to fetch tickets to our queue to work on them',
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '478700342',
                'duration' => '06:18',
                'filename' => 'Mastering Nova - Creating the Assign to Myself Action.mp4',
            ], 'creating-the-assign-to-myself-action.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Creating the Assign to Operator Action',
                'details' => 'Lets find a way to create an Action, only usable by Supervisors, and that allows us to assign a ticket to a selectable Operator',
                'is_visible' => true,
                'is_active' => true,
                'is_free' => false,
                'vimeo_id' => '476734445',
                'duration' => '06:06',
                'filename' => 'Mastering Nova - Creating the Assign to Operator Action.mp4',
            ], 'creating-the-assign-to-operator-action.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Optimizing the Assign to Myself Action',
                'details' => "Lets optimize the Assign to Myself action so it only appears on the Resource index lines that don't have an Operation assigned to",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '479413167',
                'duration' => '07:15',
                'filename' => 'Mastering Nova - Optimizing the Assign to Myself Action.mp4',
            ], 'optimizing-the-assign-to-myself-action.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Creating another Action to unassign tickets',
                'details' => "So, let's create a final action that will allow you to put back the ticket into the tickets queue, but this time you will learn a very specific behavior that Nova has",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '496310080',
                'duration' => '09:03',
                'filename' => 'Mastering Nova - Creating another Action to unassign tickets.mp4',
            ], 'creating-another-action-to-unassign-tickets.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Creating a Total Tickets UI Component Card',
                'details' => "Finally, let's put some visual KPI's to create a Total Tickets Card, from scratch, inside a new customized Dashboard",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '497615248',
                'duration' => '07:11',
                'filename' => 'Mastering Nova - Creating a Total Tickets UI Component Card.mp4',
            ], 'creating-total-ticket-card.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Resource Testing 1-o-1',
                'details' => 'A pratical scenario on how to test your Resources so you can get an initial view on it using a package made by Brian Dillingham',
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '485727247',
                'duration' => '12:13',
                'filename' => 'Mastering Nova - Resource Testing 1-o-1.mp4',
            ], 'resource-testing-1-o-1.jpg')
        );

        $chapter = Chapter::create(
            ['title' => 'Best community Packages']
        );

        $chapter->addMedia(__DIR__.'/../../resources/images/chapters/best-community-packages-social-card.jpg')
                ->preservingOriginal()
                ->toMediaCollection('social');

        $chapter->addMedia(__DIR__.'/../../resources/images/chapters/best-community-packages-featured.jpg')
                ->preservingOriginal()
                ->toMediaCollection();

        $chapter->videos()->save(
            $this->video([
                'title' => 'Search Relations',
                'details' => "By default, Laravel Nova doesn't allow you to search in the Index View, in Relationship columns. This package will make that available to you",
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '463990113',
                'duration' => '03:01',
            ], 'search-relations.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Button Field',
                'details' => 'Brian Dillingham made an awesome field that is a button that can have any action you want',
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '469964875',
                'duration' => '07:46',
                'filename' => 'Mastering Nova - Button Field.mp4',
            ], 'button-field.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Ajax Child Select',
                'details' => 'When you have several dropdowns you can connect them so the child ones will be updated given the value of the parent dropdown',
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '481001414',
                'duration' => '09:07',
                'filename' => 'Mastering Nova - Ajax Child Select Field.mp4',
            ], 'ajax-child-select.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Chart JS',
                'details' => 'This is an essential package for your testing. Brian made new assertions that will help you a lot on resource testing',
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '496860973',
                'duration' => '04:49',
                'filename' => 'Mastering Nova - Chart JS.mp4',
            ], 'nova-package-chart-js.jpg')
        );

        $chapter->videos()->save(
            $this->video([
                'title' => 'Nova Responsive',
                'details' => 'By default, Nova is not responsive. This package gives you the minimum responsiveness under the default framework',
                'is_visible' => true,
                'is_active' => true,
                'vimeo_id' => '482831792',
                'duration' => '03:35',
                'filename' => 'Mastering Nova - Responsive Package.mp4',
            ], 'responsive-package.jpg')
        );
    }

    protected function video(array $data, string $path)
    {
        $video = Video::create($data);

        $video->save();

        $video->addMedia(__DIR__."/../../resources/images/videos/{$path}")
              ->preservingOriginal()
              ->toMediaCollection();

        return $video;
    }
}
