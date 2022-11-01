<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\Tests\Unit\DependencyInjection\Compiler\BusBuilder;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use Ssch\T3Tactician\DependencyInjection\Compiler\BusBuilder\BusBuilder;
use Ssch\T3Tactician\DependencyInjection\Compiler\BusBuilder\BusBuildersFromConfig;

final class BusBuildersFromConfigTest extends UnitTestCase
{
    public function testConfigLeadsToBuilderWithDefaultForEachCommandbus()
    {
        $builders = BusBuildersFromConfig::convert([
            'commandbus' => [
                'default' => [
                    'middleware' => ['my.middleware'],
                ],
                'other' => [
                    'middleware' => ['my.other.middleware'],
                ],
            ],
        ]);

        self::assertEquals(
            new BusBuilder('default', BusBuildersFromConfig::DEFAULT_METHOD_INFLECTOR, ['my.middleware']),
            $builders->getIterator()['default']
        );
        self::assertEquals(
            new BusBuilder('other', BusBuildersFromConfig::DEFAULT_METHOD_INFLECTOR, ['my.other.middleware']),
            $builders->getIterator()['other']
        );
    }

    public function testDefaultMethodInflectorCanBeOverrided()
    {
        $builders = BusBuildersFromConfig::convert([
            'method_inflector' => 'other.inflector',
            'commandbus' => [
                'default' => [
                    'middleware' => ['my.middleware'],
                ],
                'other' => [
                    'middleware' => ['my.other.middleware'],
                ],
            ],
        ]);

        self::assertEquals(
            new BusBuilder('default', 'other.inflector', ['my.middleware']),
            $builders->getIterator()['default']
        );
    }

    public function testMethodInflectorOfEachBusCanBeOverrided()
    {
        $builders = BusBuildersFromConfig::convert([
            'method_inflector' => 'other.inflector',
            'commandbus' => [
                'default' => [
                    'middleware' => ['my.middleware'],
                ],
                'other' => [
                    'method_inflector' => 'bus2.inflector',
                    'middleware' => ['my.other.middleware'],
                ],
            ],
        ]);

        self::assertEquals(
            new BusBuilder('other', 'bus2.inflector', ['my.other.middleware']),
            $builders->getIterator()['other']
        );
    }

    public function testDefaultBusIsSet()
    {
        $builders = BusBuildersFromConfig::convert([
            'commandbus' => [
                'default' => [
                    'middleware' => ['my.middleware'],
                ],
                'other' => [
                    'middleware' => ['my.other.middleware'],
                ],
            ],
        ]);

        self::assertEquals(
            new BusBuilder('default', BusBuildersFromConfig::DEFAULT_METHOD_INFLECTOR, ['my.middleware']),
            $builders->defaultBus()
        );
    }

    public function testDefaultBusCanBeOverrided()
    {
        $builders = BusBuildersFromConfig::convert([
            'default_bus' => 'other',
            'commandbus' => [
                'default' => [
                    'middleware' => ['my.middleware'],
                ],
                'other' => [
                    'middleware' => ['my.other.middleware'],
                ],
            ],
        ]);

        self::assertEquals(
            new BusBuilder('other', BusBuildersFromConfig::DEFAULT_METHOD_INFLECTOR, ['my.other.middleware']),
            $builders->defaultBus()
        );
    }
}
