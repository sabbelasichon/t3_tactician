<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\Middleware;

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

use League\Tactician\Middleware;
use TYPO3\CMS\Extbase\Validation\ValidatorResolver;

final class ValidatorMiddleware implements Middleware
{
    public function __construct(
        private ValidatorResolver $validatorResolver
    ) {
    }

    public function execute($command, callable $next)
    {
        $validator = $this->validatorResolver->getBaseValidatorConjunction($command::class);

        $errorResult = $validator->validate($command);

        if (\count($errorResult->getFlattenedErrors()) > 0) {
            throw InvalidCommandException::onCommand($command, $errorResult);
        }

        return $next($command);
    }
}
