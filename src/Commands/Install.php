<?php

namespace Eduka\Commands;

use Eduka\Models\User;
use Illuminate\Console\Command;

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

        $this->deleteOriginalCreateUsersMigration();

        $this->migrateFresh();

        $this->deleteStorageDirectories();

        $this->createStorageLink();

        $this->installNova();

        $this->cleanCache();

        $this->publishAllResources();

        $this->publishEdukaResources();

        $this->deleteAppModelsFolder();

        $this->createAdminUser();

        $this->paragraph('-= All done! Now install your course package to start using Eduka! =-', false);

        return Command::SUCCESS;
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

    protected function deleteOriginalCreateUsersMigration()
    {
        $this->paragraph('=> Deleting original create_users migration file...', false);

        foreach (glob(database_path('migrations/*create_users*.php')) as $filename) {
            @unlink($filename);
        }
    }

    protected function migrateFresh()
    {
        $this->paragraph('=> Freshing database...', false);

        $this->call('migrate:fresh');
    }

    protected function publishEdukaResources()
    {
        $this->paragraph('=> Publishing eduka resources...', false);

        $this->call('vendor:publish', [
            '--provider' => 'Eduka\EdukaServiceProvider',
            '--force' => true,
        ]);
    }

    protected function publishAllResources()
    {
        $this->paragraph('=> Publishing all vendors resources...', false);

        $this->call('vendor:publish', [
            '--force' => true,
            '--all' => true,
        ]);
    }

    protected function cleanCache()
    {
        $this->paragraph('=> Cleaning Laravel cache...', false);

        // Clear framework cache.
        $this->call('optimize:clear');
        $this->call('view:clear');
        $this->call('key:generate');
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

        @$this->rrmdir(app_path('Models'));
    }

    protected function deleteStorageDirectories()
    {
        $this->paragraph('=> Deleting storage public directories (if they exist)...', false);

        @$this->rrmdir(storage_path('app/public'));
    }

    protected function createStorageLink()
    {
        $this->paragraph('=> Creating storage link...');

        //Delete storage if it exists.
        @$this->rrmdir(public_path('storage'));

        $this->call('storage:link');
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
    private function executeCommand($command, $path)
    {
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
