<?php

namespace App\Console\Commands;

use App\Services\User\UserService;
use Illuminate\Console\Command;

class UsersImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:import {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for import users from csv file to db';

    /**
     * Execute the console command.
     */
    public function handle(UserService $userImportService): int
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error('The specified file does not exist.');
            return 1;
        }

        $userImportService->import($file);

        $this->info('Users imported successfully.');
        return 0;
    }
}
