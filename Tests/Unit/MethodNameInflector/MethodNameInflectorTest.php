<?php

namespace Ssch\T3Tactician\Tests\Unit\MethodNameInflector;

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

use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Handler\MethodNameInflector\InvokeInflector;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use Ssch\T3Tactician\MethodNameInflector\MethodNameInflector;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AddTaskCommand;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AnotherTaskCommand;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Handler\AddTaskHandler;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Handler\AnotherTaskHandler;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

class MethodNameInflectorTest extends UnitTestCase
{
    protected $defaultMethodNameInflector;
    protected $objectManager;
    protected $configurationManager;

    protected function setUp()
    {
        $this->defaultMethodNameInflector = $this->getMockBuilder(HandleInflector::class)->getMock();
        $this->objectManager = $this->getMockBuilder(ObjectManagerInterface::class)->getMock();
        $this->configurationManager = $this->getMockBuilder(ConfigurationManagerInterface::class)->getMock();
    }

    /**
     * @test
     */
    public function defaultMehthoNameInflector()
    {
        $subject = new MethodNameInflector('default', $this->defaultMethodNameInflector, $this->objectManager, $this->configurationManager);
        $command = new AddTaskCommand();
        $commandHandler = new AddTaskHandler();
        $this->defaultMethodNameInflector->method('inflect')->with($command, $commandHandler)->willReturn('handle');
        $this->assertEquals('handle', $subject->inflect($command, $commandHandler));
    }

    /**
     * @test
     */
    public function differentMethoNameInflector()
    {
        $settings = [
            'command_bus' => [
                'default' => [
                    'method_inflector' => InvokeInflector::class,
                ],
            ],
        ];
        $this->configurationManager->expects($this->once())->method('getConfiguration')->willReturn($settings);
        $invokeInflector = $this->getMockBuilder(InvokeInflector::class)->getMock();
        $invokeInflector->method('inflect')->willReturn('__invoke');
        $this->objectManager->expects($this->once())->method('get')->willReturn($invokeInflector);
        $subject = new MethodNameInflector('default', $this->defaultMethodNameInflector, $this->objectManager, $this->configurationManager);
        $command = new AnotherTaskCommand();
        $commandHandler = new AnotherTaskHandler();
        $this->assertEquals('__invoke', $subject->inflect($command, $commandHandler));
    }
}
