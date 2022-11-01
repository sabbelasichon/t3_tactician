<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\DependencyInjection\Compiler;

use League\Tactician\CommandBus;
use Ssch\T3Tactician\DependencyInjection\Compiler\BusBuilder\BusBuildersFromConfig;
use Ssch\T3Tactician\DependencyInjection\Contract\HandlerMapping;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class CommandHandlerPass implements CompilerPassInterface
{
    public function __construct(
        private HandlerMapping $handlerMapping
    ) {
    }

    public function process(ContainerBuilder $container)
    {
        $builders = BusBuildersFromConfig::convert(
            $this->readAndForgetParameter($container, 'tactician.merged_config')
        );

        $routing = $this->handlerMapping->build($container, $builders->createBlankRouting());

        $mappings = [];

        // Register the completed builders in our container
        foreach ($builders as $builder) {
            $commandToServiceMapping = $routing->commandToServiceMapping($builder->id());
            $mappings[$builder->id()] = $commandToServiceMapping;
            $builder->registerInContainer($container, $commandToServiceMapping);
        }

        // Setup default aliases
        $container->setAlias('tactician.commandbus', $builders->defaultBus()->serviceId());
        $container->setAlias(CommandBus::class, 'tactician.commandbus');
        $container->setAlias('tactician.handler.locator.symfony', $builders->defaultBus()->locatorServiceId());
        $container->setAlias(
            'tactician.middleware.command_handler',
            $builders->defaultBus()->commandHandlerMiddlewareId()
        );

        // Wire debug command
        if ($container->hasDefinition('tactician.command.debug')) {
            $container->getDefinition('tactician.command.debug')
                ->addArgument($mappings);
        }
    }

    private function readAndForgetParameter(ContainerBuilder $container, $parameter): mixed
    {
        $value = $container->getParameter($parameter);
        $container->getParameterBag()
            ->remove($parameter);

        return $value;
    }
}
