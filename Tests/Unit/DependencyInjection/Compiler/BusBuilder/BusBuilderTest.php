<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\Tests\Unit\DependencyInjection\Compiler\BusBuilder;

use League\Tactician\CommandBus;
use League\Tactician\Container\ContainerLocator;
use Ssch\T3Tactician\DependencyInjection\Compiler\BusBuilder\BusBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ServiceLocator;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

final class BusBuilderTest extends UnitTestCase
{
    public function testDefaultNameGeneratesExpectedIds(): void
    {
        $builder = new BusBuilder('default', 'some.method.inflector', ['middleware1', 'middleware2']);

        self::assertSame('default', $builder->id());
        self::assertSame('tactician.commandbus.default', $builder->serviceId());
        self::assertSame('tactician.commandbus.default.handler.locator', $builder->locatorServiceId());
        self::assertSame(
            'tactician.commandbus.default.middleware.command_handler',
            $builder->commandHandlerMiddlewareId()
        );
    }

    public function testAlternateNameGeneratesExpectedIds(): void
    {
        $builder = new BusBuilder('foobar', 'some.method.inflector', ['middleware1', 'middleware2']);

        self::assertSame('foobar', $builder->id());
        self::assertSame('tactician.commandbus.foobar', $builder->serviceId());
        self::assertSame('tactician.commandbus.foobar.handler.locator', $builder->locatorServiceId());
        self::assertSame(
            'tactician.commandbus.foobar.middleware.command_handler',
            $builder->commandHandlerMiddlewareId()
        );
    }

    public function testProcess(): void
    {
        $builder = new BusBuilder('default', 'some.method.inflector', ['middleware1', 'middleware2']);

        $builder->registerInContainer($container = new ContainerBuilder(), []);

        $this->busShouldBeCorrectlyRegisteredInContainer($container);
    }

    private function busShouldBeCorrectlyRegisteredInContainer(ContainerBuilder $container): void
    {
        $handlerLocatorId = 'tactician.commandbus.default.handler.locator';
        $handlerId = 'tactician.commandbus.default.middleware.command_handler';

        self::assertSame(
            ServiceLocator::class,
            $container->getDefinition('tactician.commandbus.default.handler.service_locator')
                ->getClass()
        );

        self::assertSame(ContainerLocator::class, $container->getDefinition($handlerLocatorId)->getClass());

        self::assertSame('some.method.inflector', (string) $container->getDefinition($handlerId)->getArgument(2));

        self::assertTrue($container->hasAlias(CommandBus::class . ' $defaultBus'));
    }
}
