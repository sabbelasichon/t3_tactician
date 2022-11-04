<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\Middleware\Event;

final class CommandHandled implements CommandEventInterface
{
    private object $command;

    public function __construct(object $command)
    {
        $this->command = $command;
    }

    public function getCommand(): object
    {
        return $this->command;
    }

    public function getName(): string
    {
        return 'command.received';
    }
}
