<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\Tests\Unit\Factory;

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
use Ssch\T3Tactician\CommandBus\CommandBusInterface;
use Ssch\T3Tactician\CommandBusConfigurationInterface;
use Ssch\T3Tactician\Factory\CommandBusFactory;
use Ssch\T3Tactician\Middleware\MiddlewareHandlerResolverInterface;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

class CommandBusFactoryTest extends UnitTestCase
{
    /**
     * @var CommandBusFactory
     */
    protected $subject;

    private \Ssch\T3Tactician\Middleware\MiddlewareHandlerResolverInterface $middlewareHandlerResolverMock;

    private \Ssch\T3Tactician\CommandBusConfigurationInterface $commandBusConfiguration;

    protected function setUp(): void
    {
        $this->middlewareHandlerResolverMock = $this->prophesize(MiddlewareHandlerResolverInterface::class);
        $objectManager = $this->prophesize(ObjectManagerInterface::class);
        $this->commandBusConfiguration = $this->prophesize(CommandBusConfigurationInterface::class);
        $objectManager->get(CommandBusConfigurationInterface::class, 'default')->willReturn(
            $this->commandBusConfiguration->reveal()
        );
        $this->subject = new CommandBusFactory(
            $this->middlewareHandlerResolverMock->reveal(),
            $objectManager->reveal()
        );
    }


    public function testReturnsCommandBusInstance(): void
    {
        $this->middlewareHandlerResolverMock->resolveMiddlewareHandler($this->commandBusConfiguration)
            ->willReturn([]);
        $this->assertInstanceOf(CommandBusInterface::class, $this->subject->create());
    }
}
