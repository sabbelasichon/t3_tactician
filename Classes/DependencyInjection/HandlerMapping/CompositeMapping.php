<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\DependencyInjection\HandlerMapping;

use Ssch\T3Tactician\DependencyInjection\Contract\HandlerMapping;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class CompositeMapping implements HandlerMapping
{
    /**
     * @var HandlerMapping[]
     */
    private array $strategies;

    public function __construct(HandlerMapping ...$strategies)
    {
        $this->strategies = $strategies;
    }

    public function build(ContainerBuilder $container, Routing $routing): Routing
    {
        foreach ($this->strategies as $strategy) {
            $routing = $strategy->build($container, $routing);
        }

        return $routing;
    }
}
