<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\Middleware;

use function count;
use League\Tactician\Exception\Exception;
use TYPO3\CMS\Extbase\Error\Result;

final class InvalidCommandException extends \Exception implements Exception
{
    public static function onCommand($command, Result $result): self
    {
        return new static(
            'Validation failed for ' . $command::class .
            ' with ' . count($result->getFlattenedErrors()) . ' violation(s).'
        );
    }
}
