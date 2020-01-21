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

/**
 * @covers \Ssch\T3Tactician\CommandNameExtractor\HandlerExtractor
 */
class HandlerExtractorTest extends UnitTestCase
{
    /**
     * @var HandlerExtractor
     */
    protected $subject;

    /**
     * @var ClassNameExtractor
     */
    protected $classNameExtractor;

    protected function setUp()
    {
        $this->classNameExtractor = $this->prophesize(ClassNameExtractor::class);
        $this->subject = new HandlerExtractor($this->classNameExtractor->reveal());
    }

    /**
     * @test
     */
    public function extractMethodsReturnsCorrectString()
    {
        $command = new AddTaskCommand();
        $this->classNameExtractor->extract($command)->willReturn(\get_class($command));
        $this->assertEquals(\get_class($command), $this->subject->extract($command));
    }
}
