<?php

use Eduka\Seeders\InitialSchemaSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class CreateEdukaSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();

            $table->string('name')
                  ->comment('Course name');

            $table->boolean('is_active')
                  ->default(false)
                  ->comment('If a course is active then it can be bought and there is no pre-subscription');

            $table->longText('meta_tags')
                  ->comment('The HTML meta attributes that are used for social integration (twitter and facebook)')
                  ->nullable();

            $table->string('meta_image')
                  ->comment('Course social image')
                  ->nullable();

            $table->dateTimeTz('launched_at')
                  ->comment('The launch date time of your course')
                  ->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')
                  ->default(false)
                  ->comment('User admin profile. If it is admin it can have access to the backoffice and to horizon.');

            $table->boolean('is_subscribed')
                  ->default(false)
                  ->comment('When a user is created, there is a check to see if it was previously subscribed.');

            $table->longtext('properties')
                  ->comment('Stores last ip, last seen video timestamp, etc.')
                  ->after('remember_token')
                  ->nullable();

            $table->timestamp('last_login_at')
                  ->nullable()
                  ->after('remember_token');

            $table->string('invoice_link')
                  ->nullable()
                  ->after('email');

            $table->string('password')
                  ->nullable()
                  ->change();

            $table->string('name')
                  ->nullable()
                  ->change();

            $table->boolean('allows_emails')
                  ->comment('Can receive/allow emails')
                  ->after('password')
                  ->default(true);

            $table->uuid('uuid')
                  ->after('allows_emails')
                  ->nullable();

            $table->engine = 'InnoDB';
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->id();

            $table->string('code');
            $table->string('name');
            $table->decimal('ppp_index', 10, 2)
                  ->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });

        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();

            $table->string('email');

            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });

        Schema::create('chapters', function (Blueprint $table) {
            $table->id();

            $table->string('title');

            $table->unsignedInteger('index')
                  ->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });

        Schema::create('videos', function (Blueprint $table) {
            $table->id();

            $table->string('title');

            $table->text('details')
                  ->nullable();

            $table->unsignedInteger('index')
                  ->nullable();

            $table->boolean('is_visible')
                  ->comment('If the video is visible, but maybe not clickable. Useful to appear as a being-created video.')
                  ->default(false);

            $table->boolean('is_active')
                  ->comment('Video was created, and can be viewed (if allowed).')
                  ->default(false);

            $table->boolean('is_free')
                  ->default(false);

            $table->unsignedInteger('chapter_id')
                  ->nullable();

            $table->integer('duration')
                  ->comment('Total seconds, converted to i:s in the frontend via custom casts.')
                  ->nullable();

            $table->unsignedBigInteger('vimeo_id')
                  ->nullable();

            $table->string('filename')
                  ->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });

        Schema::create('links', function (Blueprint $table) {
            $table->id();

            $table->string('title');

            $table->text('url');

            $table->unsignedInteger('video_id')
                  ->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });

        Schema::create('videos_completed', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('video_id');

            $table->unsignedInteger('user_id');

            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });

        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->string('domain')
                  ->comment('Base domain without https://www.*. E.g. laravel.io, laravelnews.com, etc');

            $table->unsignedInteger('paddle_vendor_id')
                  ->comment('Paddle vendor id');

            $table->unsignedInteger('commission')
                  ->comment('Commission percentage, integer. E.g. 35 means 35 percent.');

            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });

        Schema::create('paddle_log', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('alert_id')
                  ->nullable();

            $table->string('alert_name')
                  ->nullable();

            $table->string('checkout_id')
                  ->nullable();

            $table->string('country')
                  ->nullable();

            $table->string('currency')
                  ->nullable();

            $table->string('customer_name')
                  ->nullable();

            $table->string('email')
                  ->nullable();

            $table->string('ip')
                  ->nullable();

            $table->datetime('event_time')
                  ->nullable();

            $table->unsignedBigInteger('order_id')
                  ->nullable();

            $table->string('payment_method')
                  ->nullable();

            $table->string('receipt_url')
                  ->nullable();

            $table->decimal('earnings')
                  ->comment('Net earnings received')
                  ->nullable();

            $table->decimal('payment_tax', 15, 2)
                  ->nullable();

            $table->decimal('sale_gross', 15, 2)
                  ->nullable();

            $table->decimal('fee', 15, 2)
                  ->nullable();

            $table->string('coupon')
                  ->nullable();

            $table->decimal('gross_refund', 15, 2)
                  ->nullable();

            $table->string('refund_reason')
                  ->nullable();

            $table->longtext('passthrough')
                  ->nullable();

            $table->longtext('payload')
                  ->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });

        // Delete all folders/files in the storage public directory.
        @$this->rrmdir(storage_path('app/public'));

        // Delete the public/storage folder.
        // Delete the "public/storage" folder.
        @$this->rrmdir(public_path('storage'));

        if (! is_dir(storage_path('app/public'))) {
            mkdir(storage_path('app/public'));
        }

        // Call initial schema activation
        Artisan::call('db:seed', [
            '--class' => InitialSchemaSeeder::class,
            '--force' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }

    private function rrmdir($dir)
    {
        if (is_array($dir)) {
            $files = array_diff(scandir($dir), ['.', '..']);
            foreach ($files as $file) {
                (is_dir("$dir/$file")) ? $this->rrmdir("$dir/$file") : unlink("$dir/$file");
            }
        }

        return rmdir($dir);
    }
}
