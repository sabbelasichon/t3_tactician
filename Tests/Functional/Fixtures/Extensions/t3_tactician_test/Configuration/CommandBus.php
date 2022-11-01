<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

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
        'inflector' => \League\Tactician\Handler\MethodNameInflector\InvokeInflector::class,
    ],
    'testingScheduler' => [
        'commandHandler' => [
            \Ssch\T3Tactician\Tests\Unit\Fixtures\Command\DummyScheduledCommand::class => \Ssch\T3Tactician\Tests\Unit\Fixtures\Handler\DummyScheduledHandler::class,
        ],
        'middleware' => [\Ssch\T3Tactician\Middleware\SchedulerMiddleware::class],
    ],
];
