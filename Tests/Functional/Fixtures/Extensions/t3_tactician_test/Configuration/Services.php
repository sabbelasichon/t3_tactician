<?php

use Ssch\T3TacticianTest\Command\RegisterUserCommand;
use Ssch\T3TacticianTest\Service\MyService;
use Ssch\T3TacticianTest\Handler\MyCommandHandler;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(MyService::class)->args([
        service('tactician.commandbus')
    ])->public();

    $services->set(MyCommandHandler::class)->tag('tactician.handler', [
        'command' => RegisterUserCommand::class
    ]);
};
