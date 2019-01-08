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

use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use Ssch\T3Tactician\CommandNameExtractor\HandlerExtractorInterface;
use Ssch\T3Tactician\HandlerLocator\HandlerLocatorInterface;
use Ssch\T3Tactician\MethodNameInflector\MethodNameInflectorInterface;
use Ssch\T3Tactician\Middleware\MiddlewareHandlerResolver;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use Ssch\T3Tactician\Middleware\LoggingMiddleware;

class MiddlewareHandlerResolverTest extends UnitTestCase
{

    protected $subject;
    protected $objectManager;
    protected $configurationManager;

    protected function setUp()
    {
        $this->objectManager = $this->getMockBuilder(ObjectManagerInterface::class)->getMock();
        $this->configurationManager = $this->getMockBuilder(ConfigurationManagerInterface::class)->getMock();
        $this->subject = new MiddlewareHandlerResolver($this->objectManager, $this->configurationManager);
    }

    /**
     * @test
     */
    public function onlyDefaultMiddlewareIsReturned()
    {
        $settings = [
            'command_bus' => [
                'middleware' => [],
            ],
        ];
        $this->configurationManager->expects($this->once())->method('getConfiguration')->willReturn($settings);

        $this->objectManager->method('get')->will(
            $this->returnValueMap(
                [
                    [HandlerExtractorInterface::class, $this->getMockBuilder(HandlerExtractorInterface::class)->getMock()],
                    [HandlerLocatorInterface::class, $this->getMockBuilder(HandlerLocatorInterface::class)->getMock()],
                    [MethodNameInflectorInterface::class, $this->getMockBuilder(MethodNameInflector::class)->getMock()],
                ]
            )
        );

        $middleware = $this->subject->resolveMiddlewareHandler();
        $this->assertCount(1, $middleware);
    }

    /**
     * @test
     */
    public function additionalMiddlewareIsReturned()
    {
        $settings = [
            'command_bus' => [
                'middleware' => [
                    LoggingMiddleware::class => LoggingMiddleware::class,
                ],
            ],
        ];
        $this->configurationManager->expects($this->once())->method('getConfiguration')->willReturn($settings);

        $this->objectManager->method('get')->will(
            $this->returnValueMap(
                [
                    [HandlerExtractorInterface::class, $this->getMockBuilder(HandlerExtractorInterface::class)->getMock()],
                    [HandlerLocatorInterface::class, $this->getMockBuilder(HandlerLocatorInterface::class)->getMock()],
                    [MethodNameInflectorInterface::class, $this->getMockBuilder(MethodNameInflector::class)->getMock()],
                ]
            )
        );

        $middleware = $this->subject->resolveMiddlewareHandler();
        $this->assertCount(2, $middleware);
    }


}
