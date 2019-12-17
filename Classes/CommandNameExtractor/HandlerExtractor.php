<?php
declare(strict_types = 1);

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

use League\Tactician\Exception\CanNotDetermineCommandNameException;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;

final class HandlerExtractor implements HandlerExtractorInterface
{
    /**
     * @var ClassNameExtractor
     */
    private $extractor;

    public function __construct(ClassNameExtractor $extractor)
    {
        $this->extractor = $extractor;
    }

    /**
     * Extract the name from a command
     *
     * @param object $command
     *
     * @return string
     *
     * @throws CannotDetermineCommandNameException
     */
    public function extract($command): string
    {
        return $this->extractor->extract($command);
    }
}
