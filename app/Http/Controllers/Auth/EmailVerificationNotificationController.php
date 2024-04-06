<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        abort_if($request->user()->hasVerifiedEmail(), Response::HTTP_FORBIDDEN, trans('auth.already_verified'));

        $request->user()->sendEmailVerificationNotification();

        return $this->ok(trans('auth.send_notification'));
    }
}
