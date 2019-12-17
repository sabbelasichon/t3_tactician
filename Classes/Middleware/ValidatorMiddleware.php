<?php
declare(strict_types = 1);

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
use Ssch\T3Tactician\Validator\NoValidatorFoundException;
use Ssch\T3Tactician\Validator\ValidatorResolverInterface;

final class ValidatorMiddleware implements Middleware
{
    /**
     * @var ValidatorResolverInterface
     */
    private $validatorResolver;

    public function __construct(ValidatorResolverInterface $validatorResolver)
    {
        $this->validatorResolver = $validatorResolver;
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
        try {
            $validator = $this->validatorResolver->getBaseValidatorConjunction(\get_class($command));

            $errorResult = $validator->validate($command);

            if (\count($errorResult->getFlattenedErrors()) > 0) {
                throw InvalidCommandException::onCommand($command, $errorResult);
            }
        } catch (NoValidatorFoundException $e) {
        }

        return $next($command);
    }
}
