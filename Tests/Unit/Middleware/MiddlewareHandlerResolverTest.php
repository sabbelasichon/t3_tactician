<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

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

use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;
use League\Tactician\Middleware;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use Ssch\T3Tactician\CommandBusConfigurationInterface;
use Ssch\T3Tactician\CommandNameExtractor\HandlerExtractorInterface;
use Ssch\T3Tactician\HandlerLocator\HandlerLocatorInterface;
use Ssch\T3Tactician\MethodNameInflector\MethodNameInflectorInterface;
use Ssch\T3Tactician\Middleware\LoggingMiddleware;
use Ssch\T3Tactician\Middleware\MiddlewareHandlerResolver;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

class MiddlewareHandlerResolverTest extends UnitTestCase
{
    /**
     * @var MiddlewareHandlerResolver
     */
    protected $subject;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var CommandBusConfigurationInterface
     */
    private $commandBusConfiguration;

    protected function setUp()
    {
        $this->objectManager = $this->prophesize(ObjectManagerInterface::class);
        $this->commandBusConfiguration = $this->prophesize(CommandBusConfigurationInterface::class);
        $this->commandBusConfiguration->toString()
            ->willReturn('default');
        $this->subject = new MiddlewareHandlerResolver($this->objectManager->reveal());
    }


    public function testOnlyDefaultMiddlewareIsReturned()
    {
        $this->commandBusConfiguration->middlewares()
            ->willReturn([]);

        $this->objectManager->get(HandlerExtractorInterface::class)->willReturn(
            $this->prophesize(HandlerExtractorInterface::class)->reveal()
        );
        $this->objectManager->get(HandlerLocatorInterface::class, $this->commandBusConfiguration)->willReturn(
            $this->prophesize(HandlerLocatorInterface::class)->reveal()
        );
        $this->objectManager->get(MethodNameInflectorInterface::class, $this->commandBusConfiguration)->willReturn(
            $this->prophesize(MethodNameInflector::class)->reveal()
        );

        $middleware = $this->subject->resolveMiddlewareHandler($this->commandBusConfiguration->reveal());
        $this->assertCount(1, $middleware);
    }


    public function testAdditionalMiddlewareIsReturned()
    {
        $middleware = [LoggingMiddleware::class];
        $this->commandBusConfiguration->middlewares()
            ->willReturn($middleware);

        $this->objectManager->get(LoggingMiddleware::class)->willReturn($this->prophesize(Middleware::class)->reveal());

        $this->objectManager->get(HandlerExtractorInterface::class)->willReturn(
            $this->prophesize(HandlerExtractorInterface::class)->reveal()
        );
        $this->objectManager->get(HandlerLocatorInterface::class, $this->commandBusConfiguration)->willReturn(
            $this->prophesize(HandlerLocatorInterface::class)->reveal()
        );
        $this->objectManager->get(MethodNameInflectorInterface::class, $this->commandBusConfiguration)->willReturn(
            $this->prophesize(MethodNameInflector::class)->reveal()
        );

        $middleware = $this->subject->resolveMiddlewareHandler($this->commandBusConfiguration->reveal());
        $this->assertCount(2, $middleware);
    }
}
