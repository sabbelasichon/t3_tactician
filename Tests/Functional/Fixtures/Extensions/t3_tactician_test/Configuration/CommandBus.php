<?php

return [
    'testing' => [
        'commandHandler' => [
            \Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AddTaskCommand::class => \Ssch\T3Tactician\Tests\Unit\Fixtures\Handler\AddTaskHandler::class,
        ],
        'middleware' => [
            \Ssch\T3Tactician\Middleware\LoggingMiddleware::class,
            \Ssch\T3Tactician\Middleware\ValidatorMiddleware::class,
        ],
    ],
    'testingMethodNameInflector' => [
        'commandHandler' => [
            \Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AnotherTaskCommand::class => \Ssch\T3Tactician\Tests\Unit\Fixtures\Handler\AnotherTaskHandler::class,
        ],
        'inflector' => \League\Tactician\Handler\MethodNameInflector\InvokeInflector::class
    ],
    'testingScheduler' => [
        'commandHandler' => [
            \Ssch\T3Tactician\Tests\Unit\Fixtures\Command\DummyScheduledCommand::class => \Ssch\T3Tactician\Tests\Unit\Fixtures\Handler\DummyScheduledHandler::class
        ],
        'middleware' => [
            \Ssch\T3Tactician\Middleware\SchedulerMiddleware::class
        ],
    ]
];
