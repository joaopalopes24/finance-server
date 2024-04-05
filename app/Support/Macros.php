<?php

namespace App\Support;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;

class Macros
{
    /**
     * Register the custom macros.
     */
    public static function register(): void
    {
        TestResponse::macro('assertMessage', function (string $message) {
            /** @var TestResponse $this * */
            return $this->assertJsonPath('message', $message);
        });

        JsonResource::macro('paginationInformation', function ($request, $paginated, $default) {
            Arr::forget($default, ['links', 'meta.links']);

            return $default;
        });
    }
}
