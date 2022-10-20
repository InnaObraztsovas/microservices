<?php

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class RoutsStorageTest extends KernelTestCase
{

    public function testIfRouteStore()
    {
        $cachePool = new \App\Storage\CachePool();
        $mock = $this->createMock(\App\Storage\CachePool::class);

        $serviceName = 'users';
        $existingRoutes = $cachePool->get();
        $this->assertIsArray($existingRoutes);
        $this->assertIsString($serviceName);

        $mock
            ->method('delete')
            ->willReturnSelf();
        $mock
            ->method('save')
            ->with($existingRoutes)
            ->willReturnSelf();
    }

//    public function testIfRoutMatch()
//    {
//        $collection = new \Symfony\Component\Routing\RouteCollection();
//    }
}
