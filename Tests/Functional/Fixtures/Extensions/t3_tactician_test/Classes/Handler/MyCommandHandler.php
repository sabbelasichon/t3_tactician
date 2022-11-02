<?php

declare(strict_types=1);

namespace Ssch\T3TacticianTest\Handler;

use Ssch\T3TacticianTest\Command\RegisterUserCommand;

final class MyCommandHandler
{
    public function __invoke(RegisterUserCommand $registerUserCommand): string
    {
        return 'command.executed';
    }
}
