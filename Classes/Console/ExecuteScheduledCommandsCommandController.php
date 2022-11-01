<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\Console;

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

use Ssch\T3Tactician\Command\ExecuteScheduledCommandsCommand;
use Ssch\T3Tactician\Contract\CommandBusFactoryInterface;
use Ssch\T3Tactician\Contract\CommandBusInterface;
use Symfony\Component\Console\Command\Command;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

/**
 * @codeCoverageIgnore
 */
final class ExecuteScheduledCommandsCommandController extends Command
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusFactoryInterface $commandBusFactory)
    {
        parent::__construct();
        $this->commandBus = $commandBusFactory->create();
    }

    public function executeScheduledCommandsCommand()
    {
        $executeScheduledCommandsCommand = new ExecuteScheduledCommandsCommand($this->commandBus);
        $this->commandBus->handle($executeScheduledCommandsCommand);
    }
}
