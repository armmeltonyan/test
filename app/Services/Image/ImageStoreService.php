<?php

namespace App\Services\Image;

use App\Dto\ImageDto;
use App\Enums\StorageDiskTypeEnum;
use Illuminate\Support\Facades\Storage;

class ImageStoreService
{
    public function store(ImageDto $imageDto): ?string
    {
        return Storage::disk(StorageDiskTypeEnum::IMAGES->value)->put($imageDto->path, $imageDto->image) ? Storage::disk(StorageDiskTypeEnum::IMAGES->value)->url($imageDto->path) : null;
    }
}
