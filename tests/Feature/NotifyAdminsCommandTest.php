<?php

namespace Tests\Feature;

use App\Console\Commands\UsersNotify;
use App\Mail\SendUserMail;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Tests\TestCase;

class NotifyAdminsCommandTest extends TestCase
{
    /**
     * Test that the command sends emails to administrators.
     *
     * @return void
     */
    public function test_it_sends_emails_to_admins()
    {
        // Create a fake user
        $adminUser = User::factory()->create();

        // Mock UserService
        $userServiceMock = Mockery::mock(UserService::class);
        $userServiceMock->shouldReceive('getAdmins')
            ->once()
            ->andReturn(collect([$adminUser]));

        // Replace UserService in the container with our mock
        $this->app->instance(UserService::class, $userServiceMock);

        // Mock the Mail facade
        Mail::fake();

        // Run the command
        $this->artisan(UsersNotify::class)
            ->expectsOutput('Administrators notified successfully.')
            ->assertExitCode(0);

        // Assert that the Mail facade has called queue on SendUserMail
        Mail::assertQueued(SendUserMail::class, function ($mail) use ($adminUser) {
            return $mail->hasTo($adminUser->email);
        });
    }
}
