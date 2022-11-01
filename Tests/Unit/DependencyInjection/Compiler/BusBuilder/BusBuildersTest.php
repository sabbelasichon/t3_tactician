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
use Ssch\T3Tactician\DependencyInjection\Compiler\BusBuilder\BusBuilders;
use Ssch\T3Tactician\DependencyInjection\Exception\DuplicatedCommandBusId;
use Ssch\T3Tactician\DependencyInjection\Exception\InvalidCommandBusId;
use Ssch\T3Tactician\DependencyInjection\HandlerMapping\Routing;

final class BusBuildersTest extends UnitTestCase
{
    public function testCanIterateOverBuilders()
    {
        $builders = new BusBuilders([$a, $b] = $this->buildersNamed('foo', 'bar'), 'foo');

        $this->assertSame([
            'foo' => $a,
            'bar' => $b,
        ], iterator_to_array($builders));
    }

    public function testDefaultBuilderMustBeAnIdThatActuallyExists()
    {
        $this->expectException(InvalidCommandBusId::class);

        $this->builders(['bus1'], 'some_bus_that_does_not_exist');
    }

    public function testTwoBusesCanNotHaveTheSameId()
    {
        $this->expectException(DuplicatedCommandBusId::class);

        $this->builders(['bus1', 'bus1']);
    }

    public function testBlankRoutingHasIds()
    {
        $builders = $this->builders(['bus1', 'bus2']);

        $this->assertEquals(new Routing(['bus1', 'bus2']), $builders->createBlankRouting());
    }

    private function builders($ids, $default = 'bus1'): BusBuilders
    {
        return new BusBuilders($this->buildersNamed(...$ids), $default);
    }

    private function buildersNamed(string ...$ids): array
    {
        return array_map(fn (string $id) => new BusBuilder($id, 'some.inflector', []), $ids);
    }
}
