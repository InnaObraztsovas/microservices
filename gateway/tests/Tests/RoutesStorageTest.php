<?php

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;


class RoutesStorageTest extends KernelTestCase
{

    public function testIfRouteStore()
    {
        $cachePool = new \App\Storage\CachePool();
        $mock = $this->createMock(\App\Storage\CachePool::class);
        $serviceName = 'test';
        $existingRoutes = $cachePool->get();

        $this->assertIsArray($existingRoutes);
        $this->assertIsString($serviceName);
        $this->assertArrayHasKey($serviceName, $existingRoutes);

        $mock
            ->method('delete')
            ->willReturnSelf();

        $mock
            ->method('save')
            ->with($existingRoutes)
            ->willReturnSelf();
    }

    public function testIfRoutMatch()
    {
        $collection = $this->createMock(\Symfony\Component\Routing\RouteCollection::class);
        $cachePool = new \App\Storage\CachePool();
        $existingRoutes = $cachePool->get();
        $this->assertIsArray($existingRoutes);

        foreach ($existingRoutes as $routes) {
            foreach ($routes as $name => $route) {
                $this->assertIsString($route->getHost());
                $hosts[$name] = $route->getHost();

                $route->setHost(null);

                $collection->method('add')
                    ->with($name, $route);

            }
        }

        $requestContext = $this->createMock(RequestContext::class);
        $request = new Request(); // а як мені його отримати?

        $urlMatcher = new \Symfony\Component\Routing\Matcher\UrlMatcher($collection, $requestContext);
        $match = $urlMatcher->matchRequest($request);
        $this->assertIsArray($match);
        $collection->method('get')
            ->with($match['_route'])
            ->willReturn(\Symfony\Component\Routing\Route::class);

    }
}
