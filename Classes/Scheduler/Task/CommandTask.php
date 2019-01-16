<?php
declare(strict_types = 1);

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

namespace Ssch\T3Tactician\Scheduler\Task;

use Ssch\T3Tactician\Command\ScheduledCommandInterface;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

class CommandTask extends AbstractTask
{
    private $command;

    public function __construct(ScheduledCommandInterface $command)
    {
        $this->command = $command;
        parent::__construct();
    }

    public function getCommand(): ScheduledCommandInterface
    {
        return $this->command;
    }

    public function execute()
    {
    }
}
