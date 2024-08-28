<?php

namespace App\Http\Controllers\v0\API\User;

use App\Dto\UserDto;
use App\Http\Controllers\v0\API\ApiController;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Resources\UserResource;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UserController extends ApiController
{
    public function __construct(
        protected UserService $userService,
    ) {}

    /**
     * @throws UnknownProperties
     */
    public function store(UserCreateRequest $request): JsonResponse
    {
        $userDto = new UserDto(
            name: $request->name,
            email: $request->email,
            password: $request->password
        );

        return self::sendResponse(new UserResource($this->userService->register($userDto)), __('messages.user.created'),201);
    }
}
