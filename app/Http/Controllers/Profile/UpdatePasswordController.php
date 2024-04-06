<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use Illuminate\Http\JsonResponse;

class UpdatePasswordController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdatePasswordRequest $request): JsonResponse
    {
        $request->user()->fill(['password' => $request->password])->save();

        return $this->ok(trans('messages.profile.update_password'));
    }
}
