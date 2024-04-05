<?php

namespace App\Support;

abstract class Action
{
    /**
     * Execute the action instance.
     */
    public static function handle(...$arguments): mixed
    {
        return (new static(...$arguments))->execute();
    }

    /**
     * Execute the action.
     */
    abstract protected function execute(): mixed;
}
