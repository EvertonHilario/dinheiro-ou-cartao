<?php

return [
    'default'=> env('QUEUE_CONNECTION'),
    'connections' => [
        'rabbitmq' => [
        
           'driver' => 'rabbitmq',
           'queue' => env('RABBITMQ_QUEUE', 'teste'),
           'connection' => PhpAmqpLib\Connection\AMQPLazyConnection::class,
           'hosts' => [
               [
                   'host' => env('RABBITMQ_SERVER', '127.0.0.1'),
                   'port' => env('RABBITMQ_PORT'),
                   'user' => env('RABBITMQ_USER'),
                   'password' => env('RABBITMQ_PASSWORD'),
                   'vhost' => env('RABBITMQ_VHOST'),
               ],
           ],
           'options' => [
               'ssl_options' => [
                   'cafile' => env('RABBITMQ_SSL_CAFILE', null),
                   'local_cert' => env('RABBITMQ_SSL_LOCALCERT', null),
                   'local_key' => env('RABBITMQ_SSL_LOCALKEY', null),
                   'verify_peer' => env('RABBITMQ_SSL_VERIFY_PEER', true),
                   'passphrase' => env('RABBITMQ_SSL_PASSPHRASE', null),
               ],
               'queue' => [
                   'job' => VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Jobs\RabbitMQJob::class,
               ],
           ],
       
           /*
            * Set to "horizon" if you wish to use Laravel Horizon.
            */
           'worker' => env('RABBITMQ_WORKER', 'default'),
        ],
    ],
];