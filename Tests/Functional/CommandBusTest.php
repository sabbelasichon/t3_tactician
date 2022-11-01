<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\Tests\Functional;

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

use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use Ssch\T3Tactician\Factory\CommandBusFactory;
use Ssch\T3Tactician\Middleware\InvalidCommandException;
use Ssch\T3Tactician\Scheduler\Scheduler;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AddTaskCommand;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AnotherTaskCommand;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\DummyScheduledCommand;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class CommandBusTest extends FunctionalTestCase
{
    /**
     * @var array
     */
    protected $coreExtensionsToLoad = ['scheduler'];

    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/t3_tactician',
        'typo3conf/ext/t3_tactician/Tests/Functional/Fixtures/Extensions/t3_tactician_test',
    ];

    /**
     * @var object|ObjectManager
     */
    protected $objectManager;

    protected function setUp()
    {
        parent::setUp();
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
    }


    public function testCorrectHandlerIsCalled()
    {
        $commandBus = $this->createCommandBus();
        $command = new AddTaskCommand();
        $command->title = 'Title';
        $this->assertNull($commandBus->handle($command));
    }


    public function testValidationErrorOccurredThrowsException()
    {
        $commandBus = $this->createCommandBus();
        $command = new AddTaskCommand();
        $this->expectException(InvalidCommandException::class);
        $commandBus->handle($command);
    }


    public function testScheduleCommandSuccessfully()
    {
        $command = new DummyScheduledCommand('dummy@domain.com', 'dummy');
        $command->setTimestamp(time() + 1000);
        $commandBus = $this->createCommandBus('testingScheduler');
        $commandBus->handle($command);

        $this->assertSame(
            1,
            $this->getDatabaseConnection()->selectCount('*', 'tx_scheduler_task', sprintf(
                'description = "%s"',
                Scheduler::TASK_DESCRIPTION_IDENTIFIER
            ))
        );
    }


    public function testDifferentMethodNameInflector()
    {
        $commandBus = $this->createCommandBus('testingMethodNameInflector');
        $commandBus->handle(new AnotherTaskCommand());
    }

    private function createCommandBus($name = 'testing')
    {
        return $this->objectManager->get(CommandBusFactory::class)->create($name);
    }
}
