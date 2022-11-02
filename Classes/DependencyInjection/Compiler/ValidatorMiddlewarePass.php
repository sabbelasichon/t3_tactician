<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\DependencyInjection\Compiler;

use Ssch\T3Tactician\Middleware\ValidatorMiddleware;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use TYPO3\CMS\Extbase\Validation\ValidatorResolver;

final class ValidatorMiddlewarePass implements CompilerPassInterface
{
    private const SERVICE_ID = 'tactician.middleware.validator';

    public function process(ContainerBuilder $container): void
    {
        if ($container->hasDefinition(ValidatorResolver::class) === false) {
            return;
        }

        $container->setDefinition(
            self::SERVICE_ID,
            new Definition(ValidatorMiddleware::class, [new Reference(ValidatorResolver::class)])
        );
    }
}
