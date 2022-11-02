<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\Tests\Unit\Middleware;

use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Ssch\T3Tactician\Middleware\InvalidCommandException;
use Ssch\T3Tactician\Middleware\ValidatorMiddleware;
use Ssch\T3Tactician\Tests\Unit\Fixtures\FakeCommand;
use TYPO3\CMS\Extbase\Error\Error;
use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator;
use TYPO3\CMS\Extbase\Validation\ValidatorResolver;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

final class ValidatorMiddlewareTest extends UnitTestCase
{
    use ProphecyTrait;

    private ValidatorMiddleware $subject;

    /**
     * @var ObjectProphecy|ValidatorResolver
     */
    private ObjectProphecy $validatorResolver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validatorResolver = $this->prophesize(ValidatorResolver::class);
        $this->subject = new ValidatorMiddleware($this->validatorResolver->reveal());
    }

    public function testExecuteWithErrors(): void
    {
        // Arrange
        $result = new Result();
        $result->addError(new Error('error', 1_667_333_210));

        $command = new FakeCommand();
        $notEmptyValidator = $this->prophesize(NotEmptyValidator::class);
        $notEmptyValidator->validate($command)
            ->willReturn($result);
        $this->validatorResolver->getBaseValidatorConjunction(FakeCommand::class)->willReturn($notEmptyValidator);

        // Act
        try {
            $this->subject->execute($command, function () {
            });
        } catch (InvalidCommandException $e) {
            // Assert
            self::assertEquals($result, $e->getResult());
            self::assertSame($command, $e->getCommand());
        }
    }

    public function testExecuteWithoutErrors(): void
    {
        $result = new Result();
        $command = new FakeCommand();
        $notEmptyValidator = $this->prophesize(NotEmptyValidator::class);
        $notEmptyValidator->validate($command)
            ->willReturn($result);
        $this->validatorResolver->getBaseValidatorConjunction(FakeCommand::class)->willReturn($notEmptyValidator);

        $next = fn () => 'executed';

        $actual = $this->subject->execute($command, $next);
        self::assertSame('executed', $actual);
    }
}
