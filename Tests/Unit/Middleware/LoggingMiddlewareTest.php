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

use Nimut\TestingFramework\TestCase\UnitTestCase;
use Psr\Log\LoggerInterface;
use Ssch\T3Tactician\Middleware\LoggingMiddleware;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AddTaskCommand;
use TYPO3\CMS\Core\Log\LogManagerInterface;

class LoggingMiddlewareTest extends UnitTestCase
{
    protected $subject;

    private $loggerMock;

    protected function setUp()
    {
        $this->loggerMock = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $logManagerMock = $this->getMockBuilder(LogManagerInterface::class)->getMock();
        $logManagerMock->method('getLogger')->willReturn($this->loggerMock);
        $this->subject = new LoggingMiddleware($logManagerMock);
    }

    /**
     * @test
     */
    public function innerCommandBusReceivesCommand()
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

    /**
     * @test
     */
    public function loggingIsDone()
    {
        $command = new AddTaskCommand();
        $nextClosure = function ($command) {
            $this->assertInternalType('object', $command);

            return 'foobar';
        };
        $this->loggerMock->expects($this->exactly(2))->method('info');
        $this->subject->execute($command, $nextClosure);
    }
}
