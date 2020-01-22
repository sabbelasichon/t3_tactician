<?php

namespace Ssch\T3Tactician\Tests\Unit\Middleware;

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
use PHPUnit\Framework\MockObject\MockObject;
use Ssch\T3Tactician\Middleware\InvalidCommandException;
use Ssch\T3Tactician\Middleware\ValidatorMiddleware;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AddTaskCommand;
use Ssch\T3Tactician\Validator\NoValidatorFoundException;
use Ssch\T3Tactician\Validator\ValidatorResolverInterface;
use TYPO3\CMS\Extbase\Error\Error;
use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;

/**
 * @covers \Ssch\T3Tactician\Middleware\ValidatorMiddleware
 */
class ValidatorMiddlewareTest extends UnitTestCase
{
    /**
     * @var ValidatorMiddleware
     */
    protected $subject;

    /**
     * @var MockObject|ValidatorResolverInterface
     */
    protected $validatorResolverMock;

    protected function setUp()
    {
        $this->validatorResolverMock = $this->getMockBuilder(ValidatorResolverInterface::class)->getMock();
        $this->subject = new ValidatorMiddleware($this->validatorResolverMock);
    }

    /**
     * @test
     */
    public function validationIsSuccessfulCallNext()
    {
        $validatorMock = $this->getMockBuilder(ValidatorInterface::class)->getMock();
        $errorResultMock = $this->getMockBuilder(Result::class)->getMock();
        $errorResultMock->method('getFlattenedErrors')->willReturn([]);
        $validatorMock->method('validate')->willReturn($errorResultMock);
        $this->validatorResolverMock->method('getBaseValidatorConjunction')->willReturn($validatorMock);

        $this->assertNextIsCalled();
    }

    /**
     * @test
     */
    public function noValidatorFoundSoCallNext()
    {
        $this->validatorResolverMock->method('getBaseValidatorConjunction')->willThrowException(NoValidatorFoundException::noValidatorFound(AddTaskCommand::class));
        $this->assertNextIsCalled();
    }

    private function assertNextIsCalled()
    {
        $command = new AddTaskCommand();
        $nextClosure = function ($command) {
            $this->assertInternalType('object', $command);

            return 'foobar';
        };
        $this->assertEquals(
            'foobar',
            $this->subject->execute($command, $nextClosure)
        );
    }

    /**
     * @test
     */
    public function onValidationErrorThrowsException()
    {
        $this->expectException(InvalidCommandException::class);

        $validatorMock = $this->getMockBuilder(ValidatorInterface::class)->getMock();
        $errorResultMock = $this->getMockBuilder(Result::class)->getMock();
        $errors = [
            new Error('Some error message', 1547051759)
        ];
        $errorResultMock->method('getFlattenedErrors')->willReturn($errors);
        $validatorMock->method('validate')->willReturn($errorResultMock);
        $this->validatorResolverMock->method('getBaseValidatorConjunction')->willReturn($validatorMock);

        $command = new AddTaskCommand();
        $nextClosure = function ($command) {
            $this->assertInternalType('object', $command);

            return 'foobar';
        };
        $this->subject->execute($command, $nextClosure);
    }
}
