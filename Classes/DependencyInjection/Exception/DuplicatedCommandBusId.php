<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\DependencyInjection\Exception;

final class DuplicatedCommandBusId extends \Exception
{
    public static function withId(string $id): self
    {
        $message = sprintf('There are multiple command buses with the id "%s". All bus ids must be unique.', $id);

        return new self($message);
    }
}
