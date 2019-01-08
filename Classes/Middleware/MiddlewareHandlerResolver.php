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

namespace Ssch\T3Tactician\Middleware;

use League\Tactician\Handler\CommandHandlerMiddleware;
use Ssch\T3Tactician\CommandNameExtractor\HandlerExtractorInterface;
use Ssch\T3Tactician\HandlerLocator\HandlerLocatorInterface;
use Ssch\T3Tactician\MethodNameInflector\MethodNameInflectorInterface;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

class MiddlewareHandlerResolver
{
    private $objectManager;

    private $configurationManager;

    public function __construct(ObjectManagerInterface $objectManager, ConfigurationManagerInterface $configurationManager)
    {
        $this->objectManager = $objectManager;
        $this->configurationManager = $configurationManager;
    }

    public function resolveMiddlewareHandler(): array
    {
        $middleware = [];
        foreach ($this->getRegisteredMiddlewareClassNames() as $registeredMiddlewareClassName) {
            $middleware[] = $this->objectManager->get($registeredMiddlewareClassName);
        }

        // This is required, so put it at the end
        $middleware[] = new CommandHandlerMiddleware(
            $this->objectManager->get(HandlerExtractorInterface::class),
            $this->objectManager->get(HandlerLocatorInterface::class),
            $this->objectManager->get(MethodNameInflectorInterface::class)
        );

        return $middleware;
    }

    private function getRegisteredMiddlewareClassNames(): array
    {
        $settings = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);

        return \is_array($settings['command_bus']['middleware']) ? $settings['command_bus']['middleware'] : [];
    }
}
