<?php
declare(strict_types = 1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Ssch\T3Tactician\Middleware;

use League\Tactician\Exception\Exception;
use TYPO3\CMS\Extbase\Error\Result;

final class InvalidCommandException extends \Exception implements Exception
{
    protected $command;

    protected $result;

    public static function onCommand($command, Result $result): self
    {
        $exception = new static(
            'Validation failed for ' . \get_class($command) .
            ' with ' . \count($result->getErrors()) . ' violation(s).'
        );
        $exception->command = $command;
        $exception->result = $result;

        return $exception;
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function getResult(): Result
    {
        return $this->result;
    }
}
