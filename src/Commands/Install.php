<?php

namespace Eduka\Commands;

use Eduka\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;

final class Install extends Command
{
    protected $signature = 'eduka:install';

    protected $description = 'Installs Eduka Course platform';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('
                    _       _
             ___  _| | _ _ | |__ ___
            / ._>/ . || | || / /<_> |
            \___.\___|`___||_\_\<___|

        ');

        $this->paragraph('-= Installation starting =-', false);

        $this->preChecks();

        $this->deleteNativeLaravelFiles();

        $this->replaceJsonDataTypesToLongText();

        $this->migrateFresh();

        $this->deleteStorageDirectories();

        //$this->installNova();

        $this->publishAllResources();

        $this->publishEdukaResources();

        $this->deleteAppModelsFolder();

        $this->createStorageLink();

        $this->cleanCache();

        $this->createAdminUser();

        $this->paragraph('-= All done! Now install your course package to start using Eduka! =-', false);

        return Command::SUCCESS;
    }

    protected function replaceJsonDataTypesToLongText()
    {
        $this->paragraph('=> Replacing json migration datatypes by longTexts (for maria db compatibility)...', false);

        // Delete previous create_media_file migrations.
        foreach (glob(database_path('migrations/*.php')) as $filename) {
            $file = file_get_contents($filename);

            $data = str_replace('->json(', '->longText(', $file);

            file_put_contents($filename, $data);
        }
    }

    protected function preChecks()
    {
        $this->paragraph('Running pre-checks...', false);

        /**
         * Quick ENV key/values validation.
         * key name => type
         * type can be:
         *   null (should exist, any value allowed)
         *   a value (equal to that value).
         */
        $envVars = collect([
            'EDUKA_ADMIN_NAME' => null,
            'EDUKA_ADMIN_EMAIL' => null,
            'EDUKA_ADMIN_PASSWORD' => null,
            'EDUKA_LAUNCHED' => null,
            'POSTMARK_TOKEN' => null,
            'PADDLE_SANDBOX' => null,
            'PADDLE_VENDOR_ID' => null,
            'PADDLE_VENDOR_AUTH_CODE' => null,
            'PADDLE_PUBLIC_KEY' => null,
            'QUEUE_CONNECTION' => 'redis',
            'CACHE_DRIVER' => 'redis',
            'MAIL_MAILER' => 'postmark',
        ]);

        $envVars->each(function ($value, $key) {
            if (is_null(env($key))) {
                $this->error('.env '.$key.' cannot be null / must exist');
                exit();
            } elseif (env($key) != $value && ! is_null($value)) {
                $this->error('.env '.$key.' should be equal to '.$value);
                exit();
            }
        });

        if (is_file(app_path('app/Providers/HorizonServiceProvider.php')) &&
            app()->environment() == 'production') {
            return $this->error('Please install Laravel Horizon before running Eduka');
        }

        return true;
    }

    protected function createAdminUser()
    {
        User::create([
            'name' => env('EDUKA_ADMIN_NAME'),
            'email' => env('EDUKA_ADMIN_EMAIL'),
            'password' => bcrypt(env('EDUKA_ADMIN_PASSWORD')),
        ]);
    }

    protected function deleteNativeLaravelFiles()
    {
        $this->paragraph('=> Deleting optional/unecessary Laravel files...', false);

        foreach (glob(public_path('vendor/*')) as $filename) {
            delete_all($filename);
        }

        foreach (glob(database_path('migrations/*create_media_table*.php')) as $filename) {
            delete_all($filename);
        }

        foreach (glob(resource_path('views/*')) as $filename) {
            delete_all($filename);
        }
    }

    protected function migrateFresh()
    {
        $this->paragraph('=> Freshing database...', false);

        $this->executeCommand('php artisan migrate:fresh');
    }

    protected function publishEdukaResources()
    {
        $this->paragraph('=> Publishing eduka resources...', false);

        $this->executeCommand('php artisan vendor:publish --provider=Eduka\EdukaServiceProvider --force');
    }

    protected function publishAllResources()
    {
        $this->paragraph('=> Publishing all vendors resources...', false);

        $this->executeCommand('php artisan vendor:publish --all --force', getcwd());

        /*
        Artisan::call('vendor:publish', [
            '--all' => true,
            '--force' => true,
        ]);
        */
    }

    protected function cleanCache()
    {
        $this->paragraph('=> Cleaning Laravel cache...', false);

        // Clear framework cache.
        $this->executeCommand('php artisan optimize:clear');
        $this->executeCommand('php artisan view:clear');
        $this->executeCommand('php artisan key:generate');
    }

    protected function installNova()
    {
        $this->paragraph('=> Installing Laravel Nova...', false);

        $this->call('nova:install');
        $this->call('migrate');
        $this->call('nova:publish');
    }

    protected function deleteAppModelsFolder()
    {
        $this->paragraph('=> Deleting App/Models folder...', false);

        delete_all(app_path('Models'));
    }

    protected function deleteStorageDirectories()
    {
        $this->paragraph('=> Deleting storage public directories (if they exist)...', false);

        delete_all(storage_path('app/public'));
    }

    protected function createStorageLink()
    {
        $this->paragraph('=> (re)creating storage link...');

        $this->executeCommand('php artisan storage:link --force');
    }

    private function paragraph($text, $endlf = true, $startlf = true)
    {
        if ($startlf) {
            $this->info('');
        }
        $this->info($text);
        if ($endlf) {
            $this->info('');
        }
    }

    /**
     * Run the given command as a process.
     *
     * @param  string  $command
     * @param  string  $path
     * @return void
     */
    private function executeCommand($command, $path = null)
    {
        if ($path == null) {
            $path = getcwd();
        }

        $process = (Process::fromShellCommandline($command, $path))->setTimeout(null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $process->run(function ($type, $line) {
            $this->output->write($line);
        });
    }

    /**
     * Console prompt, but enpowered with rules.
     *
     * @param string $question
     * @param string $rules
     *
     * @return mixed
     */
    private function askWithRules(string $question, string $rules)
    {
        $exit = false;
        $answer = null;

        while (! $exit) {
            $answer = $this->ask($question);
            $validator = Validator::make(
                [$question => $answer],
                [$question => $rules]
            );

            if ($validator->fails()) {
                $this->error($validator->errors()->first());
                $exit = false;
            } else {
                $exit = true;
            }
        }

        return $answer;
    }
}
