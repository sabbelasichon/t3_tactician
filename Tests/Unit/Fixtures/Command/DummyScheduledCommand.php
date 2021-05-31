<?php
declare(strict_types = 1);

namespace Ssch\T3Tactician\Tests\Unit\Fixtures\Command;

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

use Ssch\T3Tactician\Command\AbstractScheduledCommand;

final class DummyScheduledCommand extends AbstractScheduledCommand
{

    /**
     * DummyScheduledCommand constructor.
     *
     * @param string $email
     * @param string $username
     */
    public function __construct(private string $email, private string $username)
    {
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
}
