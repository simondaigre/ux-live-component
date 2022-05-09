<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\LiveComponent\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @experimental
 *
 * @internal
 */
final class ComponentDefaultActionPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->findTaggedServiceIds('twig.component') as $class => $component) {
            if (!($component[0]['live'] ?? false)) {
                continue;
            }

            $defaultAction = trim($component[0]['default_action'] ?? '__invoke', '()');

            if (!method_exists($class, $defaultAction)) {
                throw new \LogicException(sprintf('Live component "%s" (%s) requires the default action method "%s".%s', $class, $component[0]['key'], $defaultAction, '__invoke' === $defaultAction ? ' Either add this method or use the DefaultActionTrait' : ''));
            }
        }
    }
}
