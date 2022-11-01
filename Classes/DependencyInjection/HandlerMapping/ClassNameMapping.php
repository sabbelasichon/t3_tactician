<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\DependencyInjection\HandlerMapping;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class ClassNameMapping extends TagBasedMapping
{
    protected function isSupported(ContainerBuilder $container, Definition $definition, array $tagAttributes): bool
    {
        return isset($tagAttributes['command']) && class_exists(
            $container->getParameterBag()->resolveValue($tagAttributes['command'])
        );
    }

    protected function findCommandsForService(
        ContainerBuilder $container,
        Definition $definition,
        array $tagAttributes
    ): array {
        return [$container->getParameterBag() ->resolveValue($tagAttributes['command'])];
    }
}
