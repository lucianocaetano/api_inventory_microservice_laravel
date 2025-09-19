<?php

namespace Src\shared\infrastructure\providers;

use Illuminate\Support\ServiceProvider;
use Src\shared\application\contracts\out\ExtractCurrentUser;
use Src\shared\infrastructure\impl\out\ExtractCurrentUserImpl;

class ShareServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            ExtractCurrentUser::class,
            ExtractCurrentUserImpl::class
        );
    }

    public function boot()
    {
        //
    }
}
