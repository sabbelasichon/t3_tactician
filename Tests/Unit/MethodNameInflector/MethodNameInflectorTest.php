<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

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
use Ssch\T3Tactician\Contract\CommandBusConfigurationInterface;
use Ssch\T3Tactician\MethodNameInflector\MethodNameInflector;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AddTaskCommand;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AnotherTaskCommand;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Handler\AddTaskHandler;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Handler\AnotherTaskHandler;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

class MethodNameInflectorTest extends UnitTestCase
{
    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    private \Ssch\T3Tactician\Contract\CommandBusConfigurationInterface $commandBusConfiguration;

    protected function setUp()
    {
        $this->objectManager = $this->prophesize(ObjectManagerInterface::class);
        $this->commandBusConfiguration = $this->prophesize(CommandBusConfigurationInterface::class);
    }


    public function testDefaultMehthoNameInflector()
    {
        $defaultInflectorClass = HandleInflector::class;

        $this->commandBusConfiguration->inflector()
            ->willReturn($defaultInflectorClass);
        $defaultInflector = $this->prophesize($defaultInflectorClass);

        $this->objectManager->get($defaultInflectorClass)
            ->willReturn($defaultInflector);
        $subject = new MethodNameInflector($this->commandBusConfiguration->reveal(), $this->objectManager->reveal());
        $command = new AddTaskCommand();
        $commandHandler = new AddTaskHandler();

        $defaultInflector->inflect($command, $commandHandler)
            ->willReturn('handle');
        $this->assertSame('handle', $subject->inflect($command, $commandHandler));
    }


    public function testDifferentMethoNameInflector()
    {
        $this->commandBusConfiguration->inflector()
            ->willReturn(InvokeInflector::class);
        $invokeInflector = $this->prophesize(InvokeInflector::class);

        $command = new AnotherTaskCommand();
        $commandHandler = new AnotherTaskHandler();

        $invokeInflector->inflect($command, $commandHandler)
            ->willReturn('__invoke');

        $this->objectManager->get(InvokeInflector::class)->willReturn($invokeInflector->reveal());
        $subject = new MethodNameInflector($this->commandBusConfiguration->reveal(), $this->objectManager->reveal());

        $this->assertSame('__invoke', $subject->inflect($command, $commandHandler));
    }
}
