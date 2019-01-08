<?php
declare(strict_types=1);

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
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

final class HandlerLocator implements HandlerLocatorInterface
{

    private $objectManager;

    /**
     * ObjectManagerLocator constructor.
     *
     * @param $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
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
        if ( ! class_exists($commandName)) {
            throw MissingHandlerException::forCommand($commandName);
        }

        return $this->objectManager->get($commandName);
    }
}
