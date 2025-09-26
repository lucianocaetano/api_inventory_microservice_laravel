<?php

return [
    'bootstrap.servers' => env('KAFKA_HOST', 'localhost:9092'),
    'security.protocol' => env('KAFKA_SECURITY_PROTOCOL', 'SASL_SSL'),
    'sasl.mechanisms'  => env('KAFKA_SASL_MECHANISMS', 'SCRAM-SHA-512'),
    'sasl.username'    => env('KAFKA_SASL_USERNAME', 'miUsuario'),
    'sasl.password'    => env('KAFKA_SASL_PASSWORD', 'miPassword'),
    'topic' => 'inventory',
];
