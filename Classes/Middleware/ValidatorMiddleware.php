<?php
declare(strict_types=1);

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

namespace Ssch\T3Tactician\Middleware;


use League\Tactician\Middleware;
use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;

final class ValidatorMiddleware implements Middleware
{

    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param object $command
     * @param callable $next
     *
     * @return mixed
     * @throws InvalidCommandException
     */
    public function execute($command, callable $next)
    {
        $validationResult = $this->validator->validate($command);

        if ($validationResult->getErrors() > 0) {
            throw InvalidCommandException::onCommand($command, $validationResult);
        }

        return $next($command);
    }
}
