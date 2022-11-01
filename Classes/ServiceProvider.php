<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician;

use Psr\Container\ContainerInterface;
use TYPO3\CMS\Core\Package\AbstractServiceProvider;

final class ServiceProvider extends AbstractServiceProvider
{
    public function getFactories(): array
    {
        return [];
    }

    public function getExtensions(): array
    {
        return [
            'command_buses' => [self::class, 'configureCommandBuses'],
        ];
    }

    public static function configureCommandBuses(
        ContainerInterface $container,
        \ArrayObject $commandBuses,
        string $path = null
    ): \ArrayObject {
        $packageConfiguration = ($path ?? self::getPackagePath()) . 'Configuration/CommandBus.php';
        if (file_exists($packageConfiguration)) {
            $commandBusInPackage = require $packageConfiguration;
            if (is_array($commandBusInPackage)) {
                $commandBuses->exchangeArray(
                    array_replace_recursive($commandBuses->getArrayCopy(), $commandBusInPackage)
                );
            }
        }

        return $commandBuses;
    }

    protected static function getPackagePath(): string
    {
        return __DIR__ . '/../';
    }
}
