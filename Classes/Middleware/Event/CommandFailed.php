<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\Middleware\Event;

final class CommandFailed implements CommandEventInterface
{
    private bool $exceptionCaught = false;

    public function __construct(
        private readonly object $command,
        private readonly \Exception $exception
    ) {
    }

    public function getException(): \Exception
    {
        return $this->exception;
    }

    public function catchException(): void
    {
        $this->exceptionCaught = true;
    }

    public function getCommand(): object
    {
        return $this->command;
    }

    public function getName(): string
    {
        return 'command.failed';
    }

    public function isExceptionCaught(): bool
    {
        return $this->exceptionCaught;
    }
}
