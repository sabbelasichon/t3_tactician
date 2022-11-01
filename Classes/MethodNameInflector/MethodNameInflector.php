<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\MethodNameInflector;

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

use League\Tactician\Handler\MethodNameInflector\MethodNameInflector as TacticianMethoNameInflector;
use Ssch\T3Tactician\CommandBusConfigurationInterface;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

final class MethodNameInflector implements MethodNameInflectorInterface
{
    private TacticianMethoNameInflector $inflector;

    public function __construct(
        CommandBusConfigurationInterface $commandBusConfiguration,
        ObjectManagerInterface $objectManager
    ) {
        $this->inflector = $objectManager->get($commandBusConfiguration->inflector());
    }

    /**
     * Return the method name to call on the command handler and return it.
     *
     * @param object $command
     * @param object $commandHandler
     */
    public function inflect($command, $commandHandler): string
    {
        return $this->inflector->inflect($command, $commandHandler);
    }
}
