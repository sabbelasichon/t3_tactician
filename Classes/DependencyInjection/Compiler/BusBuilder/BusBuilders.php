<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\DependencyInjection\Compiler\BusBuilder;

use Ssch\T3Tactician\DependencyInjection\Exception\DuplicatedCommandBusId;
use Ssch\T3Tactician\DependencyInjection\Exception\InvalidCommandBusId;
use Ssch\T3Tactician\DependencyInjection\HandlerMapping\Routing;

/**
 * @phpstan-template \IteratorAggregate<BusBuilder>
 */
final class BusBuilders implements \IteratorAggregate
{
    /**
     * @var BusBuilder[]
     */
    private array $busBuilders = [];

    private string $defaultBusId;

    /**
     * @param BusBuilder[] $busBuilders
     */
    public function __construct(array $busBuilders, string $defaultBusId)
    {
        foreach ($busBuilders as $builder) {
            $this->add($builder);
        }

        $this->assertValidBusId($defaultBusId);
        $this->defaultBusId = $defaultBusId;
    }

    public function createBlankRouting(): Routing
    {
        return new Routing(\array_keys($this->busBuilders));
    }

    public function defaultBus(): BusBuilder
    {
        return $this->get($this->defaultBusId);
    }

    /**
     * @return \ArrayIterator<BusBuilder>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->busBuilders);
    }

    private function get(string $busId): BusBuilder
    {
        $this->assertValidBusId($busId);

        return $this->busBuilders[$busId];
    }

    private function assertValidBusId($busId)
    {
        if (! isset($this->busBuilders[$busId])) {
            throw InvalidCommandBusId::ofName($busId, array_keys($this->busBuilders));
        }
    }

    private function add(BusBuilder $builder)
    {
        $id = $builder->id();

        if (isset($this->busBuilders[$id])) {
            throw DuplicatedCommandBusId::withId($id);
        }

        $this->busBuilders[$id] = $builder;
    }
}
