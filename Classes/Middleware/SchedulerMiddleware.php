<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\Middleware;

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

use League\Tactician\Middleware;
use Ssch\T3Tactician\Command\ExecuteScheduledCommandsCommand;
use Ssch\T3Tactician\Command\ScheduledCommandInterface;
use Ssch\T3Tactician\Integration\ClockInterface;
use Ssch\T3Tactician\Scheduler\SchedulerInterface;

final class SchedulerMiddleware implements Middleware
{
    /**
     * @var SchedulerInterface
     */
    private $scheduler;

    /**
     * @var ClockInterface
     */
    private $clock;

    public function __construct(SchedulerInterface $scheduler, ClockInterface $clock)
    {
        $this->scheduler = $scheduler;
        $this->clock = $clock;
    }

    public function execute($command, callable $next)
    {
        if (($command instanceof ScheduledCommandInterface) && ($command->getTimestamp() > $this->clock->getCurrentTimestamp())) {
            return $this->scheduler->schedule($command);
        }

        if ($command instanceof ExecuteScheduledCommandsCommand) {
            $commands = $this->scheduler->getCommands();
            foreach ($commands as $scheduledCommand) {
                $command->getCommandBus()
                    ->handle($scheduledCommand);
                // Only remove command if no exception occurred
                $this->scheduler->removeCommand($scheduledCommand);
            }
        } else {
            return $next($command);
        }
    }
}
