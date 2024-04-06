<?php

/**
 * Get the frontend url.
 */
if (! function_exists('frontend')) {
    function frontend(string $path): string
    {
        return config('app.frontend_url').$path;
    }
}

/**
 * Get the limit for the request.
 */
if (! function_exists('limit')) {
    function limit(): callable
    {
        return function ($total): int {
            $limit = request()->get('limit') ?? 10;

            return min($limit, 100);
        };
    }
}
