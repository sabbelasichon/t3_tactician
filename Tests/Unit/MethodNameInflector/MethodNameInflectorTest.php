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
use Nimut\TestingFramework\TestCase\UnitTestCase;
use Ssch\T3Tactician\MethodNameInflector\MethodNameInflector;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AddTaskCommand;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Handler\AddTaskHandler;

class MethodNameInflectorTest extends UnitTestCase
{
    protected $subject;
    protected $handleClassNameInflector;

    protected function setUp()
    {
        $this->handleClassNameInflector = $this->getMockBuilder(HandleInflector::class)->getMock();
        $this->subject = new MethodNameInflector($this->handleClassNameInflector);
    }

    /**
     * @test
     */
    public function extractMethodsReturnsCorrectString()
    {
        $command = new AddTaskCommand();
        $commandHandler = new AddTaskHandler();
        $this->handleClassNameInflector->method('inflect')->with($command, $commandHandler)->willReturn('handle');
        $this->assertEquals('handle', $this->subject->inflect($command, $commandHandler));
    }
}
