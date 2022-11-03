<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\DependencyInjection\Exception;

final class InvalidCommandBusId extends \Exception
{
    /**
     * @param string[] $validIds
     */
    public static function ofName(string $expectedId, array $validIds): self
    {
        $message = sprintf(
            'Could not find a command bus with id "%s". Valid buses are: "%s"',
            $expectedId,
            implode(', ', $validIds)
        );

        return new self($message);
    }
}
