<?php
declare(strict_types=1);


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

    public function test_that_logging_is_done(): void
    {
        // Arrange
        $command = new FakeCommand();

        // Act
        $this->subject->execute($command, function() {});

        // Assert
        $this->logger->info('Starting Ssch\T3Tactician\Tests\Unit\Fixtures\FakeCommand')->shouldHaveBeenCalled();
        $this->logger->info('Ssch\T3Tactician\Tests\Unit\Fixtures\FakeCommand finished')->shouldHaveBeenCalled();
    }
}
