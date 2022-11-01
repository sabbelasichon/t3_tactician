<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\DependencyInjection\Contract;

use Ssch\T3Tactician\DependencyInjection\HandlerMapping\Routing;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface HandlerMapping
{
    public function build(ContainerBuilder $container, Routing $routing): Routing;
}
