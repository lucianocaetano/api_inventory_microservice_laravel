<?php

namespace Src\shared\infrastructure\providers;

use Illuminate\Support\ServiceProvider;
use Src\shared\application\contracts\out\EventPublisher;
use Src\shared\application\contracts\out\ExtractCurrentUser;
use Src\shared\infrastructure\impl\out\ExtractCurrentUserImpl;
use Src\shared\infrastructure\impl\out\KafkaEventPublisher;

class SharedServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            ExtractCurrentUser::class,
            ExtractCurrentUserImpl::class
        );

        $this->app->bind(
            EventPublisher::class,
            function () {
                return new KafkaEventPublisher(
                    [
                        'bootstrap.servers' => getenv('KAFKA_HOST'),
                        'topic' => 'inventory',
                        "security.protocol" => getenv('KAFKA_SECURITY_PROTOCOL'),
                        'sasl.mechanisms' => getenv('KAFKA_SASL_MECHANISM'),
                        'sasl.username' => getenv('KAFKA_SASL_USERNAME'),
                        'sasl.password' => getenv('KAFKA_SASL_PASSWORD'),
                    ]
                );
            }
        );
    }

    public function boot()
    {
        //
    }
}
