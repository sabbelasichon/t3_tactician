<?php
declare(strict_types = 1);

namespace Ssch\T3Tactician\Validator;

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

use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;

final class ValidatorResolver implements ValidatorResolverInterface
{
    private $validatorResolver;

    public function __construct(\TYPO3\CMS\Extbase\Validation\ValidatorResolver $validatorResolver)
    {
        $this->validatorResolver = $validatorResolver;
    }

    /**
     * @param string $targetClassName
     *
     * @return ValidatorInterface
     * @throws NoValidatorFoundException
     */
    public function getBaseValidatorConjunction(string $targetClassName): ValidatorInterface
    {
        $validator = $this->validatorResolver->getBaseValidatorConjunction($targetClassName);

        if (! $validator instanceof ValidatorInterface) {
            throw NoValidatorFoundException::noValidatorFound($targetClassName);
        }

        return $validator;
    }
}
