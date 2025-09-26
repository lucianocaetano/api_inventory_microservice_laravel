<?php

namespace Src\shared\infrastructure\providers;

use Illuminate\Support\ServiceProvider;
use Src\shared\application\contracts\out\EventPublisher;
use Src\shared\application\contracts\out\ExtractCurrentUser;
use Src\shared\infrastructure\impl\out\ExtractCurrentUserImpl;
use Src\shared\infrastructure\impl\out\KafkaEventPublisher;

class ShareServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            ExtractCurrentUser::class,
            ExtractCurrentUserImpl::class
        );

        $this->app->bind(
            EventPublisher::class,
            KafkaEventPublisher::class
        );
    }

    public function boot()
    {
        //
    }
}
