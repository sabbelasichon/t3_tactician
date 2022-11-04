<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\Tests\Unit\Middleware;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\EventDispatcher\EventDispatcherInterface;
use Ssch\T3Tactician\Middleware\Event\CommandFailed;
use Ssch\T3Tactician\Middleware\EventMiddleware;
use Ssch\T3Tactician\Tests\Unit\Fixtures\FakeCommand;

final class EventMiddlewareTest extends TestCase
{
    use ProphecyTrait;

    public function testThatEventsAreCalledSuccessfully(): void
    {
        // Arrange
        $eventDispatcher = new class() implements EventDispatcherInterface {
            public array $firedEvents = [];

            public function dispatch(object $event): object
            {
                $this->firedEvents[] = $event;

                return $event;
            }
        };

        $subject = new EventMiddleware($eventDispatcher);

        // Act
        $subject->execute(new FakeCommand(), function () {
        });

        // Assert
        self::assertCount(2, $eventDispatcher->firedEvents);
    }

    public function testThatFailingCommandFiresEventButThrowsExceptionInTheEnd(): void
    {
        // Assert
        $this->expectException(\Exception::class);

        // Arrange
        $eventDispatcher = new class() implements EventDispatcherInterface {
            public function dispatch(object $event): object
            {
                return $event;
            }
        };

        $subject = new EventMiddleware($eventDispatcher);

        // Act
        $subject->execute(new FakeCommand(), function () {
            throw new \Exception('Some deep failure is thrown in your application');
        });
    }

    public function testThatFailingCommandFiresEventAndCatchesException(): void
    {
        $eventDispatcher = new class() implements EventDispatcherInterface {
            public array $firedEvents = [];

            public function dispatch(object $event): object
            {
                if ($event instanceof CommandFailed) {
                    $event->catchException();
                }

                $this->firedEvents[] = $event;

                return $event;
            }
        };

        $subject = new EventMiddleware($eventDispatcher);

        // Act
        $subject->execute(new FakeCommand(), function () {
            throw new \Exception('Some deep failure is thrown in your application');
        });

        // Assert
        self::assertCount(2, $eventDispatcher->firedEvents);
    }
}
