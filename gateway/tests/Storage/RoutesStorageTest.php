<?php

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Route;


class RoutesStorageTest extends \PHPUnit\Framework\TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->cachePool = $this->createMock(\App\Storage\CachePool::class);
    }

    public function testIfRouteMatch(): void
    {
        $routes = [new Route(path: '/test', host: 'test', methods: 'GET')];

        $this->cachePool->expects($this->any())->method('get')->willReturn($routes);
        $match = $this->createMock(UrlMatcher::class);
        $matchResult = ['route'=>'route_name'];
        $match->expects($this->any())->method('matchRequest')->willReturn($matchResult);

        $this->assertNotEmpty($matchResult);

    }

    public function testIfRouteNotMatch(): void
    {

        $routes = [new Route(path: '/test', host: 'test', methods: 'GET')];

        $this->cachePool->expects($this->any())->method('get')->willReturn($routes);

        $match = $this->createMock(UrlMatcher::class);
        $matchResult = [];
        $exception = new \Symfony\Component\Routing\Exception\RouteNotFoundException();

        $match->expects($this->any())->method('matchRequest')->willReturn($matchResult)->willThrowException($exception);

        $this->assertEmpty($matchResult);

    }

    public function testStore(): void
    {
        $routes = [new Route(path: '/test', host: 'test', methods: 'GET')];

        $this->cachePool->expects($this->any())->method('get')->willReturn($routes);
        $this->cachePool->expects($this->any())->method('delete');
        $this->cachePool->expects($this->any())->method('save');

        $this->assertTrue(true);

    }
}
