<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\MethodNameInflector\ClassNameInflector;
use League\Tactician\Handler\MethodNameInflector\InvokeInflector;
use Ssch\T3Tactician\Middleware\ValidatorMiddleware;

return [
    'method_inflector' => InvokeInflector::class,
    'commandbus' => [
        'default' => [
            'middleware' => [ValidatorMiddleware::class, CommandHandlerMiddleware::class],
        ],
        'foo' => [
            'method_inflector' => ClassNameInflector::class,
            'middleware' => [ValidatorMiddleware::class, CommandHandlerMiddleware::class],
        ],
        'bar' => [
            'middleware' => [ValidatorMiddleware::class, CommandHandlerMiddleware::class],
        ],
    ],
];
