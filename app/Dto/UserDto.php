<?php

namespace App\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class UserDto extends DataTransferObject
{
    public string $name;
    public string $email;
    public string $password;
}
