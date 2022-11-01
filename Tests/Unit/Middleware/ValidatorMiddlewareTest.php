<?php
declare(strict_types=1);


namespace Ssch\T3Tactician\Tests\Unit\Middleware;


use Nimut\TestingFramework\TestCase\UnitTestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Ssch\T3Tactician\Middleware\InvalidCommandException;
use Ssch\T3Tactician\Middleware\ValidatorMiddleware;
use Ssch\T3Tactician\Tests\Unit\Fixtures\FakeCommand;
use TYPO3\CMS\Extbase\Error\Error;
use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator;
use TYPO3\CMS\Extbase\Validation\ValidatorResolver;

final class ValidatorMiddlewareTest extends UnitTestCase
{
    use ProphecyTrait;

    private ValidatorMiddleware $subject;

    private ObjectProphecy|ValidatorResolver $validatorResolver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validatorResolver = $this->prophesize(ValidatorResolver::class);
        $this->subject = new ValidatorMiddleware($this->validatorResolver->reveal());
    }

    public function test_execute_with_errors(): void
    {
        // Arrange
        $result = new Result();
        $result->addError(new Error('error', 1667333210));

        $command = new FakeCommand();
        $notEmptyValidator = $this->prophesize(NotEmptyValidator::class);
        $notEmptyValidator->validate($command)->willReturn($result);
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

    public function test_execute_without_errors(): void
    {
        $result = new Result();
        $command = new FakeCommand();
        $notEmptyValidator = $this->prophesize(NotEmptyValidator::class);
        $notEmptyValidator->validate($command)->willReturn($result);
        $this->validatorResolver->getBaseValidatorConjunction(FakeCommand::class)->willReturn($notEmptyValidator);

        $next = function () {
            return 'executed';
        };

        $actual = $this->subject->execute($command, $next);
        self::assertSame('executed', $actual);
    }
}
