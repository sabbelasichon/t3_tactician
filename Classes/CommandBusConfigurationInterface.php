<?php


namespace Ssch\T3Tactician;

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

interface CommandBusConfigurationInterface
{
    public function toString(): string;

    public function middlewares(): array;

    public function commandHandlers(): array;

    public function inflector(): string;
}
