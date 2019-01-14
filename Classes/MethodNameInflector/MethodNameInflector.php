<?php
declare(strict_types = 1);

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

use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Handler\MethodNameInflector\MethodNameInflector as TacticianMethoNameInflector;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

final class MethodNameInflector implements MethodNameInflectorInterface
{

    /**
     * @var TacticianMethoNameInflector
     */
    private $inflector;

    public function __construct($commandBusName, HandleInflector $defaultInflector, ObjectManagerInterface $objectManager, ConfigurationManagerInterface $configurationManager)
    {
        $inflectorClass = $this->getRegisteredMethodNameInflectorClassNames($commandBusName, $configurationManager);
        if ($inflectorClass === '') {
            $this->inflector = $defaultInflector;
        } else {
            $this->inflector = $objectManager->get($inflectorClass);
        }
    }

    /**
     * Return the method name to call on the command handler and return it.
     *
     * @param object $command
     * @param object $commandHandler
     *
     * @return string
     */
    public function inflect($command, $commandHandler): string
    {
        return $this->inflector->inflect($command, $commandHandler);
    }

    private function getRegisteredMethodNameInflectorClassNames(string $commandBusName, ConfigurationManagerInterface $configurationManager): string
    {
        $settings = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);

        return $settings['command_bus'][$commandBusName]['method_inflector'] ?: '';
    }
}
