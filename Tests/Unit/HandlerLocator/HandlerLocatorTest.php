<?php

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
use Ssch\T3Tactician\HandlerLocator\HandlerLocator;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AddTaskCommand;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Handler\AddTaskHandler;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

class HandlerLocatorTest extends TestCase
{
    protected $subject;

    protected $objectManager;

    protected $configurationManager;

    protected function setUp()
    {
        $this->objectManager = $this->getMockBuilder(ObjectManagerInterface::class)->getMock();
        $this->configurationManager = $this->getMockBuilder(ConfigurationManagerInterface::class)->getMock();
        $this->subject = new HandlerLocator($this->objectManager, $this->configurationManager);
    }

    /**
     * @test
     */
    public function noHandlerConfiguredForCommandThrowsException()
    {
        $this->expectException(MissingHandlerException::class);
        $this->subject->getHandlerForCommand('NotExistingClassNameForSure');
    }

    /**
     * @test
     */
    public function returnsNewCommandHandler()
    {
        $settings = [
            'command_bus' => [
                'commandHandler' => [
                    AddTaskCommand::class => AddTaskHandler::class,
                ],
            ],
        ];
        $this->configurationManager->expects($this->once())->method('getConfiguration')->willReturn($settings);
        $this->objectManager->expects($this->once())->method('get')->willReturn(new AddTaskHandler());
        $this->assertInstanceOf(AddTaskHandler::class, $this->subject->getHandlerForCommand(AddTaskCommand::class));
    }
}
