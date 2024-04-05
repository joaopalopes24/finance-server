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
