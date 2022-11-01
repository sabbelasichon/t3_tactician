<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\Tests\Unit\Scheduler;

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

use Nimut\TestingFramework\TestCase\UnitTestCase;
use Ssch\T3Tactician\Command\ScheduledCommandInterface;
use Ssch\T3Tactician\Integration\ClockInterface;
use Ssch\T3Tactician\Scheduler\Scheduler;
use Ssch\T3Tactician\Scheduler\Task\CommandTask;

class SchedulerTest extends UnitTestCase
{
    public const CURRENT_TIMESTAMP = 1;

    /**
     * @var Scheduler
     */
    protected $subject;

    private \TYPO3\CMS\Scheduler\Scheduler $scheduler;

    private \Ssch\T3Tactician\Integration\ClockInterface $clock;

    protected function setUp()
    {
        $this->clock = $this->prophesize(ClockInterface::class);
        $this->clock->getCurrentTimestamp()
            ->willReturn(self::CURRENT_TIMESTAMP);
        $this->scheduler = $this->prophesize(\TYPO3\CMS\Scheduler\Scheduler::class);
        $this->subject = new Scheduler($this->scheduler->reveal(), $this->clock->reveal());
    }


    public function testGetCommands()
    {
        $commandTask = $this->prophesize(CommandTask::class);
        $scheduledCommand = $this->prophesize(ScheduledCommandInterface::class);

        $commandTask->getCommand()
            ->willReturn($scheduledCommand->reveal());

        $commands = [$commandTask->reveal()];

        $where = sprintf(
            'nextexecution <= %d AND description = "%s"',
            self::CURRENT_TIMESTAMP,
            Scheduler::TASK_DESCRIPTION_IDENTIFIER
        );
        $this->scheduler->fetchTasksWithCondition($where, true)
            ->shouldBeCalledOnce()
            ->willReturn($commands);
        $this->assertCount(1, $this->subject->getCommands());
    }


    public function testRemoveTask()
    {
        $commandTask = $this->prophesize(CommandTask::class);
        $scheduledCommand = $this->prophesize(ScheduledCommandInterface::class);

        $commandTask->getCommand()
            ->willReturn($scheduledCommand->reveal());

        $commands = [$commandTask->reveal()];

        $where = sprintf(
            'nextexecution <= %d AND description = "%s"',
            self::CURRENT_TIMESTAMP,
            Scheduler::TASK_DESCRIPTION_IDENTIFIER
        );
        $this->scheduler->fetchTasksWithCondition($where, true)
            ->shouldBeCalledOnce()
            ->willReturn($commands);

        $this->scheduler->removeTask($commandTask)
            ->shouldBeCalledOnce();
        $this->subject->removeCommand($scheduledCommand->reveal());
    }
}
