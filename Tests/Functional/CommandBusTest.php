<?php

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
use Ssch\T3Tactician\Command\DummyCommand;
use Ssch\T3Tactician\Factory\CommandBusFactory;
use Ssch\T3Tactician\Middleware\InvalidCommandException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class CommandBusTest extends FunctionalTestCase
{
    protected $testExtensionsToLoad = ['typo3conf/ext/t3_tactician', 'typo3conf/ext/t3_tactician/Tests/Functional/Fixtures/Extensions/t3_tactician_test'];

    protected $subject;

    protected $objectManager;

    protected function setUp()
    {
        parent::setUp();
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->subject = $this->objectManager->get(CommandBusFactory::class)->create('testing');
    }

    /**
     * @test
     */
    public function correctHandlerIsCalled()
    {
        $command = new DummyCommand();
        $command->title = 'Title';
        $this->assertNull($this->subject->handle($command));
    }

    /**
     * @test
     */
    public function validationErrorOccurredThrowsException()
    {
        $command = new DummyCommand();
        $this->expectException(InvalidCommandException::class);
        $this->subject->handle($command);
    }
}
