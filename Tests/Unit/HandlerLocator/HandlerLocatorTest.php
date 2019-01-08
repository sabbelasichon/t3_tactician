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
use Ssch\T3Tactician\HandlerLocator\HandlerLocator;
use PHPUnit\Framework\TestCase;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Handler\AddTaskCommandHandler;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

class HandlerLocatorTest extends TestCase
{

    protected $subject;

    protected $objectManager;

    protected function setUp()
    {
        $this->objectManager = $this->getMockBuilder(ObjectManagerInterface::class)->getMock();
        $this->subject = new HandlerLocator($this->objectManager);
    }

    /**
     * @test
     */
    public function notExistingCommandClassThrowsException()
    {
        $this->expectException(MissingHandlerException::class);
        $this->subject->getHandlerForCommand('NotExistingClassNameForSure');
    }

    /**
     * @test
     */
    public function returnsNewCommandHandler()
    {
        $this->objectManager->expects($this->once())->method('get')->willReturn(new AddTaskCommandHandler());
        $this->assertInstanceOf(AddTaskCommandHandler::class, $this->subject->getHandlerForCommand(AddTaskCommandHandler::class));
    }
}
