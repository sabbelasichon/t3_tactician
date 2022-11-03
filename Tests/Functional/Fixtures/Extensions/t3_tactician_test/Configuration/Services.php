<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Ssch\T3Tactician\Tests\Unit\Fixtures\FakeCommand;
use Ssch\T3Tactician\Tests\Unit\Fixtures\FakeCommandHandler;
use Ssch\T3TacticianTest\Service\MyService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(MyService::class)
        ->args([service('tactician.commandbus')])->public();

    $services->set(FakeCommandHandler::class)->tag('tactician.handler', [
        'command' => FakeCommand::class,
    ]);
};
