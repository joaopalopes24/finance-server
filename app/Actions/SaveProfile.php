<?php

namespace App\Actions;

use App\Models\User;
use App\Support\Action;
use Illuminate\Auth\Events\Registered;

class SaveProfile extends Action
{
    /**
     * The user instance.
     */
    private User $user;

    /**
     * The user data.
     */
    private array $data;

    /**
     * Create a new action instance.
     */
    public function __construct(User $user, array $data)
    {
        $this->data = $data;

        $this->user = $user;
    }

    /**
     * Execute the action.
     */
    protected function execute(): User
    {
        $user = $this->user->fill($this->data);

        $this->checkIfEmailIsDirty($user);

        return $user->refresh();
    }

    /**
     * Check if the email is dirty.
     */
    private function checkIfEmailIsDirty(User $user): void
    {
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;

            event(new Registered($user));
        }

        $user->save();
    }
}
