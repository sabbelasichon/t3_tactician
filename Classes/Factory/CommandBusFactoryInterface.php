<?php
declare(strict_types = 1);

namespace Ssch\T3Tactician\Factory;

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

use Ssch\T3Tactician\CommandBus\CommandBusInterface;

interface CommandBusFactoryInterface
{
    public function create(string $commandBusName = 'default'): CommandBusInterface;
}
