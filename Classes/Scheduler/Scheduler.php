<?php
declare(strict_types = 1);

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
use Ssch\T3Tactician\Scheduler\Task\CommandTask;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\CMS\Scheduler\Execution;
use TYPO3\CMS\Scheduler\Scheduler as TYPO3Scheduler;

final class Scheduler implements SchedulerInterface
{
    const TASK_DESCRIPTION_IDENTIFIER = 'scheduler_tactician_commands';

    private $scheduler;

    private $objectManager;

    public function __construct(TYPO3Scheduler $scheduler, ObjectManagerInterface $objectManager)
    {
        $this->scheduler = $scheduler;
        $this->objectManager = $objectManager;
    }

    public function schedule(ScheduledCommandInterface $command, int $id = null): string
    {
        /** @var CommandTask $task */
        $task = $this->objectManager->get(CommandTask::class, $command);
        $task->setTaskGroup(0);
        $execution = GeneralUtility::makeInstance(Execution::class);
        $execution->setStart($command->getTimestamp());
        $task->setExecution($execution);
        $task->setDisabled(1);
        $task->setDescription(self::TASK_DESCRIPTION_IDENTIFIER);
        $this->scheduler->addTask($task);

        return (string)$task->getTaskUid();
    }

    public function getCommands(): array
    {
        /** @var CommandTask[] $tasks */
        $tasks = $this->fetchCommandTasks();

        $commands = [];
        foreach ($tasks as $task) {
            $commands[] = $task->getCommand();
            $this->scheduler->removeTask($task);
        }

        return $commands;
    }

    private function fetchCommandTasks(): array
    {
        return $this->scheduler->fetchTasksWithCondition(sprintf('nextexecution <= %d AND description = "%s"', time(), self::TASK_DESCRIPTION_IDENTIFIER), true);
    }
}
