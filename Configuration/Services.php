<?php

use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use Ssch\T3Tactician\Factory\CommandBusFactory;
use Ssch\T3Tactician\Factory\CommandBusFactoryInterface;
use Ssch\T3Tactician\Middleware\MiddlewareHandlerResolver;
use Ssch\T3Tactician\Middleware\MiddlewareHandlerResolverInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(CommandBusFactory::class)->autowire();
    $services->set(MiddlewareHandlerResolver::class)->autowire();
    $services->set(CommandBus::class);
    $services->set(ClassNameExtractor::class);

    $services->alias(MiddlewareHandlerResolverInterface::class, MiddlewareHandlerResolver::class);
    $services->alias(CommandBusFactoryInterface::class, CommandBusFactory::class);
};
