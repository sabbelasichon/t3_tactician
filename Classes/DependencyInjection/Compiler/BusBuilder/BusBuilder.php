<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\DependencyInjection\Compiler\BusBuilder;

use League\Tactician\CommandBus;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use Symfony\Component\DependencyInjection\Argument\ServiceClosureArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ServiceLocator;

final class BusBuilder
{
    public function __construct(
        private string $busId,
        private string $methodInflectorId,
        private array $middlewareIds
    ) {
    }

    public function id(): string
    {
        return $this->busId;
    }

    public function serviceId(): string
    {
        return "tactician.commandbus.{$this->busId}";
    }

    public function locatorServiceId(): string
    {
        return "tactician.commandbus.{$this->busId}.handler.locator";
    }

    public function commandHandlerMiddlewareId(): string
    {
        return "tactician.commandbus.{$this->busId}.middleware.command_handler";
    }

    public function registerInContainer(ContainerBuilder $container, array $commandsToAccept): void
    {
        $this->registerLocatorService($container, $commandsToAccept);

        $container->setDefinition(
            $this->commandHandlerMiddlewareId(),
            new Definition(
                CommandHandlerMiddleware::class,
                [
                    new Reference(ClassNameExtractor::class),
                    new Reference($this->locatorServiceId()),
                    new Reference($this->methodInflectorId),
                ]
            )
        );

        $container->setDefinition(
            $this->serviceId(),
            new Definition(
                CommandBus::class,
                [
                    \array_map(fn (string $id) => new Reference($id), $this->middlewareIds),
                ]
            )
        )->setPublic(true);

        $container->registerAliasForArgument($this->serviceId(), CommandBus::class, "{$this->busId}Bus");
    }

    private function registerLocatorService(ContainerBuilder $container, $commandsToAccept): void
    {
        $definition = new Definition(
            ContainerLocator::class,
            [
                new Reference($this->registerHandlerServiceLocator($container, $commandsToAccept)),
                $commandsToAccept,
            ]
        );

        $container->setDefinition($this->locatorServiceId(), $definition);
    }

    private function registerHandlerServiceLocator(ContainerBuilder $container, array $commandsToAccept): string
    {
        $handlers = [];
        foreach ($commandsToAccept as $handlerId) {
            $handlers[$handlerId] = new ServiceClosureArgument(new Reference($handlerId));
        }

        $handlerServiceLocator = (new Definition(ServiceLocator::class, [$handlers]))
            ->setPublic(false)
            ->addTag('container.service_locator');

        $container->setDefinition(
            $handlerId = "tactician.commandbus.{$this->busId}.handler.service_locator",
            $handlerServiceLocator
        );

        return $handlerId;
    }
}
