<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\DependencyInjection\HandlerMapping;

use Ssch\T3Tactician\DependencyInjection\Contract\HandlerMapping;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

abstract class TagBasedMapping implements HandlerMapping
{
    public const TAG_NAME = 'tactician.handler';

    public function build(ContainerBuilder $container, Routing $routing): Routing
    {
        foreach ($container->findTaggedServiceIds(self::TAG_NAME) as $serviceId => $tags) {
            foreach ($tags as $attributes) {
                $this->mapServiceByTag($container, $routing, $serviceId, $attributes);
            }
        }

        return $routing;
    }

    /**
     * @param array{typehints?: boolean, bus?: string, command?: string} $tagAttributes
     */
    abstract protected function isSupported(
        ContainerBuilder $container,
        Definition $definition,
        array $tagAttributes
    ): bool;

    /**
     * @param array{typehints?: boolean, bus?: string, command?: string} $tagAttributes
     *
     * @return string[]
     */
    abstract protected function findCommandsForService(
        ContainerBuilder $container,
        Definition $definition,
        array $tagAttributes
    ): array;

    /**
     * @param array{typehints?: boolean, bus?: string, command?: string} $attributes
     */
    private function mapServiceByTag(ContainerBuilder $container, Routing $routing, string $serviceId, array $attributes): void
    {
        $definition = $container->getDefinition($serviceId);

        if (! $this->isSupported($container, $definition, $attributes)) {
            return;
        }

        foreach ($this->findCommandsForService($container, $definition, $attributes) as $commandClassName) {
            if (isset($attributes['bus'])) {
                $routing->routeToBus($attributes['bus'], $commandClassName, $serviceId);
            } else {
                $routing->routeToAllBuses($commandClassName, $serviceId);
            }
        }
    }
}
