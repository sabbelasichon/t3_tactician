<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3TacticianTest\Service;

use League\Tactician\CommandBus;
use Ssch\T3Tactician\Tests\Unit\Fixtures\OtherFakeCommand;

final class MyServiceWithAutowiredCommandBusFoo
{
    private CommandBus $fooBus;

    public function __construct(CommandBus $fooBus)
    {
        $this->fooBus = $fooBus;
    }

    public function handleOtherFakeCommand(): string
    {
        return $this->fooBus->handle(new OtherFakeCommand());
    }
}
