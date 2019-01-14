<?php
declare(strict_types = 1);

namespace Ssch\T3Tactician\HandlerLocator;

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

use League\Tactician\Exception\MissingHandlerException;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

final class HandlerLocator implements HandlerLocatorInterface
{
    private $objectManager;
    private $configurationManager;
    private $commandBusName;

    public function __construct(string $commandBusName, ObjectManagerInterface $objectManager, ConfigurationManagerInterface $configurationManager)
    {
        $this->commandBusName = $commandBusName;
        $this->objectManager = $objectManager;
        $this->configurationManager = $configurationManager;
    }

    /**
     * Retrieves the handler for a specified command
     *
     * @param string $commandName
     *
     * @return object
     *
     * @throws MissingHandlerException
     */
    public function getHandlerForCommand($commandName)
    {
        $registeredHandlers = $this->getRegisteredMehthodInflectorClassName($this->commandBusName);

        if (! isset($registeredHandlers[$commandName])) {
            throw MissingHandlerException::forCommand($commandName);
        }

        if (! class_exists($registeredHandlers[$commandName])) {
            throw MissingHandlerException::forCommand($commandName);
        }

        return $this->objectManager->get($registeredHandlers[$commandName]);
    }

    private function getRegisteredMehthodInflectorClassName(string $commandBusName): array
    {
        $settings = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);

        return \is_array($settings['command_bus'][$commandBusName]['commandHandler']) ? $settings['command_bus'][$commandBusName]['commandHandler'] : [];
    }
}
