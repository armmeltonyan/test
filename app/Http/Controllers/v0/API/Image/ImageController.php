<?php

namespace App\Http\Controllers\v0\API\Image;

use App\Http\Controllers\v0\API\ApiController;
use App\Http\Requests\Image\ImageStoreRequets;
use App\Jobs\ImageUpload;
use App\Services\Image\ImageSqueezeService;
use App\Services\Image\ImageStoreService;
use Exception;
use Illuminate\Http\JsonResponse;

class ImageController extends ApiController
{
    public function __construct(
        protected ImageStoreService $imageStoreService,
        protected ImageSqueezeService $imageSqueezeService
    ) {}

    /**
     * @throws Exception
     */
    public function store(ImageStoreRequets $request): JsonResponse
    {
        ImageUpload::dispatch($request->file('image'));

        return self::sendResponse(null, __('messages.image.upload_queued'), 202);
    }
}
