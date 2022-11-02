<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\DependencyInjection\HandlerMapping;

use ReflectionClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Routes commands based on typehints in the handler.
 *
 * If your handler has a public method with a single, non-scalar, no-interface type hinted parameter, we'll assume that
 * typehint is a command and route it to this service definition as the handler.
 *
 * So, a class like this:
 *
 * class MyHandler { public function handle(RegisterUser $command) {...} private function foobar(SomeObject $obj) {...}
 * public function checkThings(OtherObject $obj, WhatObject $obj2) public function setADependency(ManagerInterface
 * $interface) {...} }
 *
 * would have RegisterUser routed to it, but not SomeObject (because it's used in a private method), not OtherObject or
 * WhatObject (because they don't appear as the only parameter) and not setADependency (because it has an interface type
 * hinted parameter).
 */
final class TypeHintMapping extends TagBasedMapping
{
    /**
     * @param array{typehints?: boolean} $tagAttributes
     */
    protected function isSupported(ContainerBuilder $container, Definition $definition, array $tagAttributes): bool
    {
        return isset($tagAttributes['typehints']) && $tagAttributes['typehints'] === true;
    }


    protected function findCommandsForService(
        ContainerBuilder $container,
        Definition $definition,
        array $tagAttributes
    ): array {
        $results = [];

        $reflectionClass = new ReflectionClass($container->getParameterBag()->resolveValue($definition->getClass()));

        foreach ($reflectionClass->getMethods() as $method) {
            if (! $method->isPublic()
                || $method->isConstructor()
                || $method->isStatic()
                || $method->isAbstract()
                || $method->isVariadic()
                || $method->getNumberOfParameters() !== 1
            ) {
                continue;
            }

            $parameter = $method->getParameters()[0];
            if (! $parameter->hasType()
                || $parameter->getType() instanceof \ReflectionUnionType
                || $parameter->getType()
                    ->isBuiltin()
                || (new ReflectionClass($parameter->getType()->getName()))->isInterface()
            ) {
                continue;
            }

            $results[] = $parameter->getType()->getName();
        }

        return $results;
    }
}
