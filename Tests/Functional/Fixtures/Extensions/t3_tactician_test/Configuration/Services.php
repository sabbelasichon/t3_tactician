<?php

use Ssch\T3TacticianTest\Service\MyService;
use Ssch\T3TacticianTest\Handler\MyCommandHandler;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(MyCommandHandler::class)->public();
    $services->set(MyService::class)->args([
        service('tactician.commandbus')
    ])->public();
};
