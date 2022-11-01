<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\DependencyInjection\HandlerMapping;

use Ssch\T3Tactician\DependencyInjection\Exception\InvalidCommandBusId;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

final class Routing
{
    private array $mapping;

    public function __construct(array $validBusIds)
    {
        foreach ($validBusIds as $validBusId) {
            $this->mapping[$validBusId] = [];
        }
    }

    public function routeToBus($busId, $commandClassName, $serviceId): void
    {
        $this->assertValidBusId($busId);
        $this->assertValidCommandFQCN($commandClassName, $serviceId);

        $this->mapping[$busId][$commandClassName] = $serviceId;
    }

    public function routeToAllBuses($commandClassName, $serviceId): void
    {
        $this->assertValidCommandFQCN($commandClassName, $serviceId);

        foreach ($this->mapping as $busId => $mapping) {
            $this->mapping[$busId][$commandClassName] = $serviceId;
        }
    }

    public function commandToServiceMapping(string $busId): array
    {
        $this->assertValidBusId($busId);
        return $this->mapping[$busId];
    }

    private function assertValidBusId(string $busId): void
    {
        if (! isset($this->mapping[$busId])) {
            throw InvalidCommandBusId::ofName($busId, array_keys($this->mapping));
        }
    }

    private function assertValidCommandFQCN(string $commandClassName, string $serviceId): void
    {
        if (! class_exists($commandClassName)) {
            throw new InvalidArgumentException(
                "Can not route {$commandClassName} to {$serviceId}, class {$commandClassName} does not exist!"
            );
        }
    }
}
