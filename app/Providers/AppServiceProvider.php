<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Note;
use App\Models\Task;
use App\Policies\NotePolicy;
use App\Policies\TaskPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Note::class => NotePolicy::class,
        Task::class => TaskPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
