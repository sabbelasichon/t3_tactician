<?php

namespace Ssch\T3Tactician\Tests\Unit\Validator;

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

use Nimut\TestingFramework\TestCase\UnitTestCase;
use Ssch\T3Tactician\Command\DummyCommand;
use Ssch\T3Tactician\Validator\NoValidatorFoundException;
use Ssch\T3Tactician\Validator\ValidatorResolver;
use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;

class ValidatorResolverTest extends UnitTestCase
{
    protected $subject;

    protected $validatorResolverMock;

    protected function setUp()
    {
        $this->validatorResolverMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Validation\ValidatorResolver::class)->disableOriginalConstructor()->getMock();
        $this->subject = new ValidatorResolver($this->validatorResolverMock);
    }

    /**
     * @test
     * @throws NoValidatorFoundException
     */
    public function noValidatorFoundThrowsException()
    {
        $this->expectException(NoValidatorFoundException::class);
        $this->validatorResolverMock->method('getBaseValidatorConjunction')->willReturn(null);
        $this->subject->getBaseValidatorConjunction(DummyCommand::class);
    }

    /**
     * @test
     */
    public function returnCorrectValidator()
    {
        $validatorMock = $this->getMockBuilder(ValidatorInterface::class)->getMock();
        $this->validatorResolverMock->method('getBaseValidatorConjunction')->willReturn($validatorMock);
        $this->assertInstanceOf(ValidatorInterface::class, $this->subject->getBaseValidatorConjunction(DummyCommand::class));
    }
}
