<?php

namespace App\Repositories\User;

use App\Enums\UserRolesEnum;
use App\Models\User;
use Illuminate\Support\Enumerable;

class UserRepository
{
    public function batchInsert(array $batch): void
    {
        User::insert($batch);
    }

    public function getAdministrators(): ?Enumerable
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', UserRolesEnum::ADMINISTRATOR->value);
        })->get();
    }

    public function create($userDto): User
    {
        return User::create($userDto->toArray());
    }

    public function update(int $id, array $updateData): void
    {
        User::whereId($id)->update($updateData);
    }
}
