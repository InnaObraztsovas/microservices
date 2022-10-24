<?php

use Symfony\Component\Routing\Route;


class RoutesStorageTest extends \PHPUnit\Framework\TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->storage = new \App\Storage\RoutesStorage(new \App\Storage\CachePool(new \Symfony\Component\Cache\Adapter\ArrayAdapter()));
    }


    public function testStore(): void
    {

        $this->storage->store('users', new Route('/path1'), new Route('/path2'));

        $this->assertTrue(true);

    }

    public function testMatch(): void
    {
        $this->storage->store('users', new Route('/path1'), new Route('/path2'));
        $route = $this->storage->match(\Symfony\Component\HttpFoundation\Request::create('/path1'));
        $this->assertInstanceOf(Route::class, $route);

        $this->expectException(\Symfony\Component\Routing\Exception\ResourceNotFoundException::class);
        $this->storage->match(\Symfony\Component\HttpFoundation\Request::create('/path3'));

    }
}
