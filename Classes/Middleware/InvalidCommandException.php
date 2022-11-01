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

final class InvalidCommandException extends \RuntimeException implements Exception
{
    private object $command;

    private Result $result;

    public static function onCommand(object $command, Result $result): self
    {
        $exception = new self(
            'Validation failed for ' . $command::class .
            ' with ' . count($result->getFlattenedErrors()) . ' violation(s).'
        );

        $exception->command = $command;
        $exception->result = $result;

        return $exception;
    }

    public function getCommand(): object
    {
        return $this->command;
    }

    public function getResult(): Result
    {
        return $this->result;
    }
}
