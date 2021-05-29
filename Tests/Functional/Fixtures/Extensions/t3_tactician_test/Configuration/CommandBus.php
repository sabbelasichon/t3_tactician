<?php

use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AddTaskCommand;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Handler\AddTaskHandler;
use Ssch\T3Tactician\Middleware\LoggingMiddleware;
use Ssch\T3Tactician\Middleware\ValidatorMiddleware;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AnotherTaskCommand;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Handler\AnotherTaskHandler;
use League\Tactician\Handler\MethodNameInflector\InvokeInflector;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\DummyScheduledCommand;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Handler\DummyScheduledHandler;
use Ssch\T3Tactician\Middleware\SchedulerMiddleware;
return [
    'testing' => [
        'commandHandler' => [
            AddTaskCommand::class => AddTaskHandler::class,
        ],
        'middleware' => [
            LoggingMiddleware::class,
            ValidatorMiddleware::class,
        ],
    ],
    'testingMethodNameInflector' => [
        'commandHandler' => [
            AnotherTaskCommand::class => AnotherTaskHandler::class,
        ],
        'inflector' => InvokeInflector::class
    ],
    'testingScheduler' => [
        'commandHandler' => [
            DummyScheduledCommand::class => DummyScheduledHandler::class
        ],
        'middleware' => [
            SchedulerMiddleware::class
        ],
    ]
];
