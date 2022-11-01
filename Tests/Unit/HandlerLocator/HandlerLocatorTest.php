<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\Tests\Unit\HandlerLocator;

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

use League\Tactician\Exception\MissingHandlerException;
use PHPUnit\Framework\TestCase;
use Ssch\T3Tactician\Contract\CommandBusConfigurationInterface;
use Ssch\T3Tactician\HandlerLocator\HandlerLocator;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AddTaskCommand;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Handler\AddTaskHandler;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

class HandlerLocatorTest extends TestCase
{
    /**
     * @var HandlerLocator
     */
    protected $subject;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    private \Ssch\T3Tactician\CommandBusConfiguration $commandBusConfiguration;

    protected function setUp()
    {
        $this->objectManager = $this->prophesize(ObjectManagerInterface::class);
        $this->commandBusConfiguration = $this->prophesize(CommandBusConfigurationInterface::class);
        $this->subject = new HandlerLocator($this->commandBusConfiguration->reveal(), $this->objectManager->reveal());
    }


    public function testNoHandlerConfiguredForCommandThrowsException()
    {
        $this->expectException(MissingHandlerException::class);
        $this->commandBusConfiguration->commandHandlers()
            ->willReturn([]);
        $this->subject->getHandlerForCommand('NotExistingClassNameForSure');
    }


    public function testNotExistingHandlerClassForCommandThrowsException()
    {
        $this->expectException(MissingHandlerException::class);
        $this->commandBusConfiguration->commandHandlers()
            ->willReturn([
                'NotExistingClassNameForSure' => 'Handler',
            ]);
        $this->subject->getHandlerForCommand('NotExistingClassNameForSure');
    }


    public function testReturnsNewCommandHandler()
    {
        $commandHandlers = [
            AddTaskCommand::class => AddTaskHandler::class,
        ];

        $this->commandBusConfiguration->commandHandlers()
            ->willReturn($commandHandlers);
        $this->objectManager->get(AddTaskHandler::class)->willReturn(new AddTaskHandler());
        $this->assertInstanceOf(AddTaskHandler::class, $this->subject->getHandlerForCommand(AddTaskCommand::class));
    }
}
