<?php

namespace Ssch\T3Tactician\Tests\Unit;

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
use Ssch\T3Tactician\CommandAlreadyAssignedToHandlerException;
use Ssch\T3Tactician\CommandBusConfiguration;
use Ssch\T3Tactician\Integration\FilesystemInterface;
use Ssch\T3Tactician\MethodNameInflector\MethodNameInflector;
use Ssch\T3Tactician\Middleware\LoggingMiddleware;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AddTaskCommand;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Handler\AddTaskHandler;
use TYPO3\CMS\Core\Package\PackageInterface;
use TYPO3\CMS\Core\Package\PackageManager;

/**
 * @covers \Ssch\T3Tactician\CommandBusConfiguration
 * @covers \Ssch\T3Tactician\CommandAlreadyAssignedToHandlerException
 */
class CommandBusConfigurationTest extends UnitTestCase
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @var PackageManager
     */
    private $packageManager;

    protected function setUp()
    {
        $this->filesystem = $this->prophesize(FilesystemInterface::class);
        $this->packageManager = $this->prophesize(PackageManager::class);
    }

    /**
     * @test
     */
    public function addCommandBusConfigurationSuccessfully()
    {
        $activePackage = $this->prophesize(PackageInterface::class);
        $activePackage->getPackagePath()->willReturn('foo/');

        $activePackages = [
            $activePackage->reveal()
        ];

        $commandBusConfiguration = [
            '_default' => [
                'commandHandler' => [
                    AddTaskCommand::class => AddTaskHandler::class,
                ],
                'middleware' => [
                    LoggingMiddleware::class
                ],
                'inflector' => InvokeInflector::class
            ],
        ];

        $this->packageManager->getActivePackages()->willReturn($activePackages);
        $this->filesystem->isFile('foo/Configuration/CommandBus.php')->willReturn(true);
        $this->filesystem->require('foo/Configuration/CommandBus.php')->willReturn($commandBusConfiguration);

        $subject = new CommandBusConfiguration('_default', $this->packageManager->reveal(), $this->filesystem->reveal());
        $this->assertSame('_default', $subject->toString());
        $this->assertSame($commandBusConfiguration['_default']['commandHandler'], $subject->commandHandlers());
        $this->assertSame($commandBusConfiguration['_default']['middleware'], $subject->middlewares());
        $this->assertSame($commandBusConfiguration['_default']['inflector'], $subject->inflector());
    }

    /**
     * @test
     */
    public function addMultipleCommandBusConfigurationWithSameCommandHandlerThrowsException()
    {
        $activePackage1 = $this->prophesize(PackageInterface::class);
        $activePackage1->getPackagePath()->willReturn('foo/');

        $activePackage2 = $this->prophesize(PackageInterface::class);
        $activePackage2->getPackagePath()->willReturn('bar/');

        $activePackages = [
            $activePackage1->reveal(),
            $activePackage2->reveal()
        ];

        $commandBusConfiguration = [
            '_default' => [
                'commandHandler' => [
                    AddTaskCommand::class => AddTaskHandler::class,
                ],
                'middleware' => [

                ],
                'method_inflector' => [

                ]
            ],
        ];

        $this->packageManager->getActivePackages()->willReturn($activePackages);
        $this->filesystem->isFile('foo/Configuration/CommandBus.php')->willReturn(true);
        $this->filesystem->require('foo/Configuration/CommandBus.php')->willReturn($commandBusConfiguration);

        $this->filesystem->isFile('bar/Configuration/CommandBus.php')->willReturn(true);
        $this->filesystem->require('bar/Configuration/CommandBus.php')->willReturn($commandBusConfiguration);

        $this->expectException(CommandAlreadyAssignedToHandlerException::class);

        $subject = new CommandBusConfiguration('_default', $this->packageManager->reveal(), $this->filesystem->reveal());
        $this->assertSame('_default', $subject->toString());
        $this->assertSame($commandBusConfiguration['_default']['commandHandler'], $subject->commandHandlers());
    }
}
