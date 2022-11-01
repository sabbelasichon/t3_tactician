<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\CommandNameExtractor;

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

use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use Ssch\T3Tactician\Contract\HandlerExtractorInterface;

final class HandlerExtractor implements HandlerExtractorInterface
{
    public function __construct(private ClassNameExtractor $extractor)
    {
    }

    /**
     * Extract the name from a command
     *
     * @param object $command
     */
    public function extract($command): string
    {
        return $this->extractor->extract($command);
    }
}
