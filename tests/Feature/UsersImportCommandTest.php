<?php

namespace Tests\Feature;

use App\Services\User\UserService;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class UsersImportCommandTest extends TestCase
{
    /**
     * Test the command runs successfully with a valid file.
     *
     * @return void
     */
    public function test_command_runs_successfully_with_valid_file(): void
    {
        $file = 'users.csv';
        Storage::fake();
        Storage::put($file, 'id,name,email\n1,John Doe,john@example.com');

        $userServiceMock = Mockery::mock(UserService::class);
        $this->app->instance(UserService::class, $userServiceMock);
        $userServiceMock->shouldReceive('import')->once()->with(Storage::path($file));

        $this->artisan('users:import', ['file' => Storage::path($file)])
            ->expectsOutput('Users imported successfully.')
            ->assertExitCode(0);
    }

    /**
     * Test the command fails when the file does not exist.
     *
     * @return void
     */
    public function test_command_fails_when_file_does_not_exist(): void
    {
        $file = 'non_existent_file.csv';

        $this->artisan('users:import', ['file' => $file])
            ->expectsOutput('The specified file does not exist.')
            ->assertExitCode(1);
    }
}
