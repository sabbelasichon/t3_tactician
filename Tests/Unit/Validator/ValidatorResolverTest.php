<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

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
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AddTaskCommand;
use Ssch\T3Tactician\Validator\NoValidatorFoundException;
use Ssch\T3Tactician\Validator\ValidatorResolver;
use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;

class ValidatorResolverTest extends UnitTestCase
{
    /**
     * @var ValidatorResolver
     */
    protected $subject;

    /**
     * @var ObjectProphecy|\TYPO3\CMS\Extbase\Validation\ValidatorResolver
     */
    protected $validatorResolver;

    protected function setUp()
    {
        $this->validatorResolver = $this->prophesize(\TYPO3\CMS\Extbase\Validation\ValidatorResolver::class);
        $this->subject = new ValidatorResolver($this->validatorResolver->reveal());
    }


    public function testNoValidatorFoundThrowsException()
    {
        $this->expectException(NoValidatorFoundException::class);
        $this->validatorResolver->getBaseValidatorConjunction(Argument::any())->willReturn(null);
        $this->subject->getBaseValidatorConjunction(AddTaskCommand::class);
    }


    public function testReturnCorrectValidator()
    {
        $validator = $this->prophesize(ValidatorInterface::class);
        $this->validatorResolver->getBaseValidatorConjunction(Argument::any())->willReturn($validator->reveal());
        $this->assertInstanceOf(
            ValidatorInterface::class,
            $this->subject->getBaseValidatorConjunction(AddTaskCommand::class)
        );
    }
}
