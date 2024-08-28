<?php

namespace App\Http\Controllers\v0\API\Image;

use App\Http\Controllers\v0\API\ApiController;
use App\Http\Requests\Image\ImageStoreRequets;
use App\Http\Resources\Image\ImageResource;
use App\Services\Image\ImageSqueezeService;
use App\Services\Image\ImageStoreService;
use Exception;
use Illuminate\Http\JsonResponse;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class ImageController extends ApiController
{
    public function __construct(
        protected ImageStoreService $imageStoreService,
        protected ImageSqueezeService $imageSqueezeService
    ) {}

    /**
     * @throws UnknownProperties
     * @throws Exception
     */
    public function store(ImageStoreRequets $request): JsonResponse
    {
        $imageDto = $this->imageSqueezeService->squeeze($request->file('image')->getRealPath());
        $image = $this->imageStoreService->store($imageDto);

        return $image === null
            ? self::sendError(__('errors.image.upload'), 500)
            : self::sendResponse(new ImageResource($image), __('messages.image.upload'),201);
    }
}
