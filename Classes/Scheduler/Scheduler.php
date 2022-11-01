<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

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
use Ssch\T3Tactician\Integration\ClockInterface;
use Ssch\T3Tactician\Scheduler\Task\CommandTask;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\Execution;
use TYPO3\CMS\Scheduler\Scheduler as TYPO3Scheduler;

final class Scheduler implements SchedulerInterface
{
    public const TASK_DESCRIPTION_IDENTIFIER = 'scheduler_tactician_commands';

    public function __construct(private TYPO3Scheduler $scheduler, private ClockInterface $clock)
    {
    }

    public function schedule(ScheduledCommandInterface $command, int $id = null): string
    {
        /** @var CommandTask $task */
        $task = new CommandTask($command);
        $task->setTaskGroup(0);
        $execution = GeneralUtility::makeInstance(Execution::class);
        $execution->setStart($command->getTimestamp());
        $task->setExecution($execution);
        $task->setDisabled(1);
        $task->setDescription(self::TASK_DESCRIPTION_IDENTIFIER);
        $this->scheduler->addTask($task);

        return (string) $task->getTaskUid();
    }

    public function getCommands(): array
    {
        return array_map(static fn(CommandTask $commandTask) => $commandTask->getCommand(), $this->fetchCommandTasks());
    }

    public function removeCommand(ScheduledCommandInterface $command)
    {
        /** @var CommandTask[] $tasks */
        $tasks = $this->fetchCommandTasks();

        foreach ($tasks as $task) {
            if ($command === $task->getCommand()) {
                $this->scheduler->removeTask($task);
            }
        }
    }

    private function fetchCommandTasks(): array
    {
        return $this->scheduler->fetchTasksWithCondition(
            sprintf(
                'nextexecution <= %d AND description = "%s"',
                $this->clock->getCurrentTimestamp(),
                self::TASK_DESCRIPTION_IDENTIFIER
            ),
            true
        );
    }
}
