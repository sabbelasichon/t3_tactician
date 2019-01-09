<?php

namespace Ssch\T3Tactician\Tests\Unit\CommandNameExtractor;

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

use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use Ssch\T3Tactician\CommandNameExtractor\HandlerExtractor;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AddTaskCommand;

class HandlerExtractorTest extends UnitTestCase
{
    protected $subject;
    protected $classNameExtractMock;

    protected function setUp()
    {
        $this->classNameExtractMock = $this->getMockBuilder(ClassNameExtractor::class)->getMock();
        $this->subject = new HandlerExtractor($this->classNameExtractMock);
    }

    /**
     * @test
     */
    public function extractMethodsReturnsCorrectString()
    {
        $command = new AddTaskCommand();
        $this->classNameExtractMock->method('extract')->with($command)->willReturn(\get_class($command));
        $this->assertEquals(\get_class($command), $this->subject->extract($command));
    }
}