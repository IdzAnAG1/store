<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    //
    function creating(User $user): void
    {
        Log::info('UserObserver::creating вызван');
        $user->role_id = 1;
    }
}
