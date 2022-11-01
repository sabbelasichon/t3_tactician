<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

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

use League\Tactician\CommandBus;
use Ssch\T3Tactician\Factory\CommandBusFactoryInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

/**
 * @codeCoverageIgnore
 */
final class ExecuteScheduledCommandsCommandController extends CommandController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CommandBusFactoryInterface $commandBusFactory)
    {
        $this->commandBus = $commandBusFactory->create();
    }

    public function executeScheduledCommandsCommand()
    {
        $executeScheduledCommandsCommand = new ExecuteScheduledCommandsCommand($this->commandBus);
        $this->commandBus->handle($executeScheduledCommandsCommand);
    }
}
