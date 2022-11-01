<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

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
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Ssch\T3Tactician\Middleware\InvalidCommandException;
use Ssch\T3Tactician\Middleware\ValidatorMiddleware;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AddTaskCommand;
use Ssch\T3Tactician\Validator\NoValidatorFoundException;
use Ssch\T3Tactician\Validator\ValidatorResolverInterface;
use TYPO3\CMS\Extbase\Error\Error;
use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;

class ValidatorMiddlewareTest extends UnitTestCase
{
    /**
     * @var ValidatorMiddleware
     */
    protected $subject;

    /**
     * @var ObjectProphecy|ValidatorResolverInterface
     */
    protected $validatorResolver;

    protected function setUp()
    {
        $this->validatorResolver = $this->prophesize(ValidatorResolverInterface::class);
        $this->subject = new ValidatorMiddleware($this->validatorResolver->reveal());
    }


    public function testValidationIsSuccessfulCallNext()
    {
        $validator = $this->prophesize(ValidatorInterface::class);
        $errorResult = $this->prophesize(Result::class);
        $errorResult->getFlattenedErrors()
            ->willReturn([]);
        $validator->validate(Argument::any())->willReturn($errorResult);

        $this->validatorResolver->getBaseValidatorConjunction(Argument::any())->willReturn($validator->reveal());

        $this->assertNextIsCalled();
    }


    public function testNoValidatorFoundSoCallNext()
    {
        $this->validatorResolver->getBaseValidatorConjunction(Argument::any())->willThrow(
            NoValidatorFoundException::noValidatorFound(AddTaskCommand::class)
        );
        $this->assertNextIsCalled();
    }


    public function testOnValidationErrorThrowsException()
    {
        $this->expectException(InvalidCommandException::class);

        $validator = $this->prophesize(ValidatorInterface::class);
        $errorResult = $this->prophesize(Result::class);
        $errors = [new Error('Some error message', 1547051759)];
        $errorResult->getFlattenedErrors()
            ->willReturn($errors);
        $validator->validate(Argument::any())->willReturn($errorResult);
        $this->validatorResolver->getBaseValidatorConjunction(Argument::any())->willReturn($validator->reveal());

        $command = new AddTaskCommand();
        $nextClosure = function ($command) {
            $this->assertInternalType('object', $command);

            return 'foobar';
        };
        $this->subject->execute($command, $nextClosure);
    }

    private function assertNextIsCalled()
    {
        $command = new AddTaskCommand();
        $nextClosure = function ($command) {
            $this->assertInternalType('object', $command);

            return 'foobar';
        };
        $this->assertEquals('foobar', $this->subject->execute($command, $nextClosure));
    }
}
