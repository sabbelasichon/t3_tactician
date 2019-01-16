<?php

namespace Ssch\T3Tactician\Tests\Unit\Middleware;

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

use League\Tactician\CommandBus;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use Ssch\T3Tactician\Command\DummyCommand;
use Ssch\T3Tactician\Command\ExecuteScheduledCommandsCommand;
use Ssch\T3Tactician\Command\ScheduledCommandInterface;
use Ssch\T3Tactician\Integration\ClockInterface;
use Ssch\T3Tactician\Middleware\SchedulerMiddleware;
use Ssch\T3Tactician\Scheduler\SchedulerInterface;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AddTaskCommand;

class SchedulerMiddlewareTest extends UnitTestCase
{
    protected $subject;

    protected $scheduler;

    protected $clock;

    protected function setUp()
    {
        $this->scheduler = $this->getMockBuilder(SchedulerInterface::class)->getMock();
        $this->clock = $this->getMockBuilder(ClockInterface::class)->getMock();
        $this->subject = new SchedulerMiddleware($this->scheduler, $this->clock);
    }

    /**
     * @test
     */
    public function scheduleCommand()
    {
        $command = $this->getMockBuilder(ScheduledCommandInterface::class)->getMock();
        $command->method('getTimestamp')->willReturn(time());
        $this->scheduler->expects($this->once())->method('schedule')->with($command);
        $this->clock->method('getCurrentTimestamp')->willReturn(time() - 1000);
        $this->subject->execute($command, function () {
        });
    }

    /**
     * @test
     */
    public function executeCommands()
    {
        $commandBus = $this->getMockBuilder(CommandBus::class)->disableOriginalConstructor()->getMock();
        $command = new ExecuteScheduledCommandsCommand($commandBus);
        $this->scheduler->expects($this->never())->method('schedule');
        $commands = [
            new DummyCommand(),
            new DummyCommand(),
            new DummyCommand(),
        ];
        $this->scheduler->expects($this->once())->method('getCommands')->willReturn($commands);
        $commandBus->expects($this->exactly(\count($commands)))->method('handle');
        $this->subject->execute($command, function () {
        });
    }

    /**
     * @test
     */
    public function callNextCallable()
    {
        $command = new AddTaskCommand();
        $nextClosure = function ($command) {
            $this->assertInternalType('object', $command);

            return 'foobar';
        };
        $this->assertEquals(
            'foobar',
            $this->subject->execute($command, $nextClosure)
        );
    }
}
