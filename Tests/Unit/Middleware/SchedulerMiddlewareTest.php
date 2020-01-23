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
use PHPUnit\Framework\MockObject\MockObject;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Ssch\T3Tactician\Command\ExecuteScheduledCommandsCommand;
use Ssch\T3Tactician\Command\ScheduledCommandInterface;
use Ssch\T3Tactician\Integration\ClockInterface;
use Ssch\T3Tactician\Middleware\SchedulerMiddleware;
use Ssch\T3Tactician\Scheduler\SchedulerInterface;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AddTaskCommand;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\DummyScheduledCommand;
use function count;

/**
 * @covers \Ssch\T3Tactician\Middleware\SchedulerMiddleware
 */
class SchedulerMiddlewareTest extends UnitTestCase
{
    /**
     * @var SchedulerMiddleware
     */
    protected $subject;

    /**
     * @var ObjectProphecy|SchedulerInterface
     */
    protected $scheduler;

    /**
     * @var ClockInterface
     */
    protected $clock;

    protected function setUp()
    {
        $this->scheduler = $this->prophesize(SchedulerInterface::class);
        $this->clock = $this->prophesize(ClockInterface::class);
        $this->subject = new SchedulerMiddleware($this->scheduler->reveal(), $this->clock->reveal());
    }

    /**
     * @test
     */
    public function scheduleCommand()
    {
        $command = $this->prophesize(ScheduledCommandInterface::class);
        $timestamp = time();
        $command->getTimestamp()->willReturn($timestamp);
        $this->scheduler->schedule($command)->shouldBeCalledOnce();
        $this->clock->getCurrentTimestamp()->willReturn($timestamp - 1000);
        $this->subject->execute($command->reveal(), static function () {
        });
    }

    /**
     * @test
     */
    public function executeCommands()
    {
        $commandBus = $this->prophesize(CommandBus::class);
        $command = new ExecuteScheduledCommandsCommand($commandBus->reveal());
        $this->scheduler->schedule(new DummyScheduledCommand('email@domain.com', 'username'))->shouldNotBeCalled();

        $commands = [
            new DummyScheduledCommand('email@domain.com', 'username'),
            new DummyScheduledCommand('email@domain.com', 'username'),
            new DummyScheduledCommand('email@domain.com', 'username'),
        ];

        $this->scheduler->getCommands()->shouldBeCalledOnce()->willReturn($commands);

        $this->subject->execute($command, static function () {
        });

        $commandBus->handle(Argument::type(ScheduledCommandInterface::class))->shouldHaveBeenCalledTimes(count($commands));
        $this->scheduler->removeCommand(Argument::any())->shouldHaveBeenCalledTimes(count($commands));
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
