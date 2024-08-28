<?php

namespace App\Console\Commands;

use App\Mail\SendUserMail;
use App\Services\User\UserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class UsersNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for sending notification to all administrators';

    /**
     * Execute the console command.
     */
    public function handle(UserService $userService): void
    {
        $userService->getAdmins()->each(function ($user) {
            Mail::to($user->email)->queue(new SendUserMail($user));
        });

        $this->info('Administrators notified successfully.');
    }
}
