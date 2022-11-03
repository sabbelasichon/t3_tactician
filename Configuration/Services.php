<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator, ContainerBuilder $containerBuilder): void {
    $services = $containerConfigurator->services();
    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->load('Ssch\\T3Tactician\\', __DIR__ . '/../Classes/')->exclude([
        __DIR__ . '/../Classes/Middleware/InvalidCommandException.php',
        __DIR__ . '/../Classes/Validator/NoValidatorFoundException.php',
        __DIR__ . '/../Classes/CommandAlreadyAssignedToHandlerException.php',
    ]);
};
