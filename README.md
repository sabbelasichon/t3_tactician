# TYPO3 Tactician
[![Build Status](https://travis-ci.org/sabbelasichon/t3_tactician.png)](https://travis-ci.org/sabbelasichon/t3_tactician)

TYPO3 Extension for the Tactician library
[https://github.com/thephpleague/tactician/](https://github.com/thephpleague/tactician/)

## Installation

```bash
$ composer require ssch/t3-tactician
```

## Using the Command Bus

Create a class and inject the command bus factory:

```php
<?php

namespace Vendor\MyExtension\Controller;

use Ssch\T3Tactician\Factory\CommandBusFactoryInterface;
use Ssch\T3Tactician\Command\DummyCommand;

class YourNameController
{
    private $commandBus;
    
    public function __construct(CommandBusFactoryInterface $commandBus)
    {
        $this->commandBus = $commandBus->create();
    }
    
    public function doSomethingAction()
    {
        $command = new DummyCommand();
        $this->commandBus->handle($command);
    }
}
```

## Configuring Command Handlers
So, in order to handle your commands with a specific handler you have to configure it via TypoScript the following way:

```
config.tx_extbase {
    command_bus {
        default {
            commandHandler {
                Vendor\MyExtension\Command\MyCommand = Vendor\MyExtension\Handler\MyHandler
            }
        }
    }
}
```

## Middleware

The extension ships with a few pre-configured middlewares (i.e. LoggingMiddleware).
To enable them, add them to the middlewares list in your bus configuration via TypoScript:

```
config.tx_extbase {
    command_bus {
        default {
            middleware {
                Ssch\T3Tactician\Middleware\LoggingMiddleware = Ssch\T3Tactician\Middleware\LoggingMiddleware
            }
        }
    }
}
```

You can also create your own middleware and add them to the configuration.
The ordering in the configuration is important.

The very last middleware per default is the Command Handler Middleware bundled in Tactician.
This is the plugin that actually matches your command to a handler and executes it.

If you really need to customize this, feel free to contact me. Actually you can. But this is not part of the documentation. 

## Customizing the MethodNameInflector

By default, the extension uses `HandleInflector` from Tactician core. That is to say, it expects your Command Handlers to have a `handle()` method that receives the command to execute.

However, [if you prefer a different inflector](http://tactician.thephpleague.com/tweaking-tactician/), you can override this via TypoScript configuration:

```
config.tx_extbase {
    objects {
        Ssch\T3Tactician\MethodNameInflector\MethodNameInflectorInterface.className = Vendor\MyExtension\MyMethodNameInflector
    }
}
```

The class MyMethodNameInflector is an adapter and has to implement the MethodNameInflectorInterface.
