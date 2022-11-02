<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use Ssch\T3Tactician\Middleware\ValidatorMiddleware;

return [
    'method_inflector' => HandleInflector::class,
    'commandbus' => [
        'default' => [
            'middleware' => [
                ValidatorMiddleware::class,
            ],
        ],
    ],
];
