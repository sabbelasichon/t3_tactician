<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\Tests\Unit\Middleware;

use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use Ssch\T3Tactician\Middleware\LoggingMiddleware;
use Ssch\T3Tactician\Tests\Unit\Fixtures\FakeCommand;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

final class LoggingMiddlewareTest extends UnitTestCase
{
    use ProphecyTrait;

    private LoggerInterface|ObjectProphecy $logger;

    private LoggingMiddleware $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logger = $this->prophesize(LoggerInterface::class);
        $this->subject = new LoggingMiddleware();
        $this->subject->setLogger($this->logger->reveal());
    }

    public function testThatLoggingIsDone(): void
    {
        // Arrange
        $command = new FakeCommand();

        // Act
        $this->subject->execute($command, function () {
        });

        // Assert
        $this->logger->info('Starting Ssch\T3Tactician\Tests\Unit\Fixtures\FakeCommand')
            ->shouldHaveBeenCalled();
        $this->logger->info('Ssch\T3Tactician\Tests\Unit\Fixtures\FakeCommand finished')
            ->shouldHaveBeenCalled();
    }
}
