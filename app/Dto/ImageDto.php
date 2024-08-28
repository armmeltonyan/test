<?php

namespace App\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class ImageDto extends DataTransferObject
{
    public string $image;
    public string $path;
}
