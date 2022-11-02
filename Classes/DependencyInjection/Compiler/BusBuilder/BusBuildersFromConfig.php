<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\DependencyInjection\Compiler\BusBuilder;

use League\Tactician\Handler\MethodNameInflector\HandleInflector;

final class BusBuildersFromConfig
{
    public const DEFAULT_METHOD_INFLECTOR = HandleInflector::class;

    private const DEFAULT_BUS_ID = 'default';

    /**
     * @return BusBuilders<BusBuilder>
     */
    public static function convert(array $config): BusBuilders
    {
        $defaultInflector = $config['method_inflector'] ?? self::DEFAULT_METHOD_INFLECTOR;

        $builders = [];
        foreach ($config['commandbus'] ?? [] as $busId => $busConfig) {
            $builders[] = new BusBuilder(
                $busId,
                $busConfig['method_inflector'] ?? $defaultInflector,
                $busConfig['middleware']
            );
        }

        return new BusBuilders($builders, $config['default_bus'] ?? self::DEFAULT_BUS_ID);
    }
}
