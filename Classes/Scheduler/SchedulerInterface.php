<?php

namespace Ssch\T3Tactician\Scheduler;

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

use Ssch\T3Tactician\Command\ScheduledCommandInterface;

interface SchedulerInterface
{
    public function schedule(ScheduledCommandInterface $command, int $id = null): string;

    public function getCommands(): array;

    public function removeCommand(ScheduledCommandInterface $command);
}
