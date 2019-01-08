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
use Ssch\T3Tactician\Factory\CommandBusFactory;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AddTaskCommand;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class CommandBusTest extends FunctionalTestCase
{
    protected $testExtensionsToLoad = ['typo3conf/ext/t3_tactician'];

    protected $subject;

    protected $objectManager;

    protected function setUp()
    {
        parent::setUp();
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->subject = $this->objectManager->get(CommandBusFactory::class)->create();
    }

    /**
     * @test
     */
    public function correctHandlerIsCalled()
    {
        $this->assertNull($this->subject->handle(new AddTaskCommand()));
    }
}
