<?php
declare(strict_types=1);


namespace Ssch\T3Tactician\DependencyInjection\Compiler;


use Ssch\T3Tactician\Contract\ValidatorResolverInterface;
use Ssch\T3Tactician\Middleware\ValidatorMiddleware;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use TYPO3\CMS\Extbase\Validation\ValidatorResolver;

final class ValidatorMiddlewarePass implements CompilerPassInterface
{
    private const SERVICE_ID = 'tactician.middleware.validator';

    public function process(ContainerBuilder $container)
    {
        if(false === $container->hasDefinition(ValidatorResolver::class)) {
            return;
        }

        $container->setDefinition(self::SERVICE_ID, new Definition(ValidatorMiddleware::class, [new Reference(ValidatorResolver::class)]));
    }
}
