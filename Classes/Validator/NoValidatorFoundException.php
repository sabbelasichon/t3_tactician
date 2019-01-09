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

use League\Tactician\Exception\Exception;

final class NoValidatorFoundException extends \Exception implements Exception
{
    public static function noValidatorFound(string $targetClassName): self
    {
        return new static(sprintf('No validator found for %s', $targetClassName));
    }
}
