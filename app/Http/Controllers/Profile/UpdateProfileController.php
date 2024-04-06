<?php

namespace App\Http\Controllers\Profile;

use App\Actions\SaveProfile;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Resources\Profile\UpdateProfileResource;
use Illuminate\Http\JsonResponse;

class UpdateProfileController extends Controller
{
    /**
     * Update the user's profile.
     */
    public function __invoke(UpdateProfileRequest $request): JsonResponse
    {
        $user = SaveProfile::handle($request->user(), $request->input());

        return UpdateProfileResource::make($user)->response();
    }
}
