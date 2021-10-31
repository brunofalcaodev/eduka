<?php

namespace Eduka\Commands;

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

        $this->checkAdminCredentials();

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

        return 0;
    }

    protected function createAdminUser()
    {
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

    protected function checkAdminCredentials()
    {
        /*
         * Create the admin user using env credentials:
         * EDUKA_ADMIN_NAME
         * EDUKA_ADMIN_EMAIL
         * EDUKA_ADMIN_PASSWORD
         */

        if (!env('EDUKA_ADMIN_NAME') || !env('EDUKA_ADMIN_EMAIL') || !env('EDUKA_ADMIN_PASSWORD')) {
            $this->error('Check your ENV credentials: EDUKA_ADMIN_NAME, EDUKA_ADMIN_EMAIL and EDUKA_ADMIN_PASSWORD');
            exit();
        }
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
