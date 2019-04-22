<?php

namespace App\Providers;

use App\Kudos;
use App\Policies\KudosPolicy;
use App\Policies\ReviewResultPolicy;
use App\ReviewResult;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        ReviewResult::class => ReviewResultPolicy::class,
        Kudos::class => KudosPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
