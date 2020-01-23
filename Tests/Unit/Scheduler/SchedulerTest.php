<?php

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
use Ssch\T3Tactician\Scheduler\Scheduler;
use Ssch\T3Tactician\Scheduler\Task\CommandTask;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

/**
 * @covers \Ssch\T3Tactician\Scheduler\Scheduler
 */
class SchedulerTest extends UnitTestCase
{
    const TASK_UID = 1;
    protected $objectManager;
    protected $scheduler;
    protected $subject;
    private $commandTask;

    protected function setUp()
    {
        $this->objectManager = $this->getMockBuilder(ObjectManagerInterface::class)->getMock();
        $this->commandTask = $this->getMockBuilder(CommandTask::class)->disableOriginalConstructor()->getMock();
        $this->commandTask->method('getTaskUid')->willReturn(self::TASK_UID);
        $this->objectManager->method('get')->willReturn($this->commandTask);
        $this->scheduler = $this->getMockBuilder(\TYPO3\CMS\Scheduler\Scheduler::class)->disableOriginalConstructor()->getMock();
        $this->subject = new Scheduler($this->scheduler, $this->objectManager);
    }

    /**
     * @test
     */
    public function scheduleCommand()
    {
        $command = $this->getMockBuilder(ScheduledCommandInterface::class)->getMock();
        $this->commandTask->expects($this->once())->method('setDescription')->with(Scheduler::TASK_DESCRIPTION_IDENTIFIER);
        $this->scheduler->expects($this->once())->method('addTask')->with($this->commandTask);
        $this->assertEquals(self::TASK_UID, $this->subject->schedule($command));
    }
}
