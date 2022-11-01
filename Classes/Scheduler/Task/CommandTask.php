<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\Scheduler\Task;

use Ssch\T3Tactician\Contract\ScheduledCommandInterface;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * @codeCoverageIgnore
 */
class CommandTask extends AbstractTask
{
    public function __construct(private ScheduledCommandInterface $command)
    {
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
