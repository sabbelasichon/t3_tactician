<?php
declare(strict_types = 1);

namespace Ssch\T3Tactician\Tests\Unit\Fixtures\Handler;

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

use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\AnotherTaskCommand;

final class AnotherTaskHandler
{
    public function __invoke(AnotherTaskCommand $anotherTaskCommand)
    {
    }
}
