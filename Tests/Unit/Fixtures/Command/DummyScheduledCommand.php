<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

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
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $username;

    /**
     * DummyScheduledCommand constructor.
     */
    public function __construct(string $email, string $username)
    {
        $this->email = $email;
        $this->username = $username;
    }


    public function getEmail(): string
    {
        return $this->email;
    }


    public function getUsername(): string
    {
        return $this->username;
    }
}
