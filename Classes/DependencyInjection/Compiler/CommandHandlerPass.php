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
use League\Tactician\Handler\CommandHandlerMiddleware;
use Ssch\T3Tactician\Command\DebugCommand;
use Ssch\T3Tactician\DependencyInjection\Compiler\BusBuilder\BusBuildersFromConfig;
use Ssch\T3Tactician\DependencyInjection\Contract\HandlerMapping;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Package\PackageManager;

final class CommandHandlerPass implements CompilerPassInterface
{
    private HandlerMapping $handlerMapping;

    public function __construct(HandlerMapping $handlerMapping)
    {
        $this->handlerMapping = $handlerMapping;
    }

    public function process(ContainerBuilder $container): void
    {
        $config = $this->createCommandBusConfigurationFromPackages();

        if ($config->count() === 0) {
            return;
        }

        $builders = BusBuildersFromConfig::convert($config->getArrayCopy());
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
        $container->setAlias(CommandHandlerMiddleware::class, $builders->defaultBus()->commandHandlerMiddlewareId());
        $container->setAlias(
            'tactician.middleware.command_handler',
            $builders->defaultBus()
                ->commandHandlerMiddlewareId()
        );

        // Wire debug command
        if ($container->hasDefinition(DebugCommand::class)) {
            $container->getDefinition(DebugCommand::class)
                ->addArgument($mappings);
        }
    }

    private function createCommandBusConfigurationFromPackages(): \ArrayObject
    {
        $coreCache = Bootstrap::createCache('core');
        $packageCache = Bootstrap::createPackageCache($coreCache);
        $packageManager = Bootstrap::createPackageManager(PackageManager::class, $packageCache);

        $config = new \ArrayObject();
        foreach ($packageManager->getAvailablePackages() as $package) {
            $commandBusConfigurationFile = $package->getPackagePath() . 'Configuration/CommandBus.php';
            if (file_exists($commandBusConfigurationFile)) {
                $commandBusInPackage = require $commandBusConfigurationFile;
                if (is_array($commandBusInPackage)) {
                    $config->exchangeArray(array_replace_recursive($config->getArrayCopy(), $commandBusInPackage));
                }
            }
        }

        return $config;
    }
}
