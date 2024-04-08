<?php

test('it should return a frontend url', function () {
    $url = frontend('/test');

    expect($url)->toBe(config('app.frontend_url').'/test');
});

test('it should return the limit for the request', function () {
    $request = request();

    $request->merge(['limit' => 50]);

    $limit = limit()($request);

    expect($limit)->toBe(50);

    $request->merge(['limit' => 200]);

    $limit = limit()($request);

    expect($limit)->toBe(100);
});

test('it should check if helpers exists', function () {
    expect(function_exists('limit'))->toBeTrue();

    expect(function_exists('frontend'))->toBeTrue();
});
