<?php
declare(strict_types = 1);

namespace Ssch\T3Tactician\Command;

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

final class DummyScheduledCommand extends AbstractScheduledCommand
{

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $username;

    /**
     * @var DummyCommand
     */
    private $subCommand;

    /**
     * DummyScheduledCommand constructor.
     *
     * @param string $email
     * @param string $username
     */
    public function __construct(string $email, string $username)
    {
        $this->email = $email;
        $this->username = $username;
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
