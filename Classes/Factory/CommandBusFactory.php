<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\Factory;

use League\Tactician\CommandBus;
use Ssch\T3Tactician\CommandBus\CommandBusInterface;
use Ssch\T3Tactician\CommandBus\TacticianCommandBus;
use Ssch\T3Tactician\CommandBusConfigurationInterface;
use Ssch\T3Tactician\Middleware\MiddlewareHandlerResolverInterface;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

final class CommandBusFactory implements SingletonInterface, CommandBusFactoryInterface
{
    public function __construct(private MiddlewareHandlerResolverInterface $middlewareHandlerResolver, private ObjectManagerInterface $objectManager)
    {
    }

    public function create(string $commandBusName = 'default'): CommandBusInterface
    {
        $commandBusConfiguration = $this->objectManager->get(CommandBusConfigurationInterface::class, $commandBusName);

        return new TacticianCommandBus(new CommandBus($this->middlewareHandlerResolver->resolveMiddlewareHandler(
            $commandBusConfiguration
        )));
    }
}
