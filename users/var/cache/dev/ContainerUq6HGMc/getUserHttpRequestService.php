<?php

namespace ContainerUq6HGMc;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getUserHttpRequestService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private 'App\Command\UserHttpRequest' shared autowired service.
     *
     * @return \App\Command\UserHttpRequest
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/symfony/console/Command/Command.php';
        include_once \dirname(__DIR__, 4).'/src/Command/UserHttpRequest.php';

        $container->privates['App\\Command\\UserHttpRequest'] = $instance = new \App\Command\UserHttpRequest(($container->services['router'] ?? $container->getRouterService()));

        $instance->setName('user:create-http');

        return $instance;
    }
}
