<?php

namespace App\Services\User;

use App\Dto\UserDto;
use App\Models\User;
use App\Repositories\User\UserRepository;
use App\Services\CsvHandleService;
use Illuminate\Support\Enumerable;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected CsvHandleService $csvHandleService
    ) {}

    public function register(UserDto $userDto): User
    {
        return $this->userRepository->create($userDto);
    }

    public function import(string $csvFile): void
    {
        $usersBatch = $this->csvHandleService->handle($csvFile);
        $this->userRepository->batchInsert($usersBatch);
    }

    public function getAdmins(): Enumerable
    {
        return $this->userRepository->getAdministrators();
    }
}
