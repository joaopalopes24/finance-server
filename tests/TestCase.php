<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;
use ReflectionClass;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Seed the database before each test.
     */
    protected bool $seed = true;

    /**
     * Create a access token for the user.
     */
    public function createToken(User $user): NewAccessToken
    {
        return $user->createToken('api-token');
    }

    /**
     * Get the personal access token from the database.
     */
    public function getPersonalAccessToken(string $token): PersonalAccessToken
    {
        return PersonalAccessToken::findToken($token);
    }

    /**
     * Call protected / private method of a class.
     */
    public function invokeMethod(mixed $object, string $method, array $parameters = []): mixed
    {
        $reflection = new ReflectionClass(get_class($object));

        $method = $reflection->getMethod($method);

        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Get protected / private property of a class.
     */
    public function invokeProperty(mixed $object, string $property): mixed
    {
        $reflection = new ReflectionClass(get_class($object));

        $property = $reflection->getProperty($property);

        $property->setAccessible(true);

        return $property->getValue($object);
    }
}
