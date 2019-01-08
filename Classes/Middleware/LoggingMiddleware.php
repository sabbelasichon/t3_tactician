<?php
declare(strict_types=1);


namespace Ssch\T3Tactician\Middleware;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use League\Tactician\Middleware;
use TYPO3\CMS\Core\Log\LogManagerInterface;

final class LoggingMiddleware implements Middleware
{

    private $logger;

    public function __construct(LogManagerInterface $logManager)
    {
        $this->logger = $logManager->getLogger(__CLASS__);
    }

    public function execute($command, callable $next)
    {
        $commandClass = \get_class($command);

        $this->logger->info(sprintf('Starting %s', $commandClass));
        $returnValue = $next($command);
        $this->logger->info(sprintf('%s finished', $commandClass));

        return $returnValue;
    }


}
