<?php
namespace App\Storage;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Routing\Route;

class CachePool
{
    private FilesystemAdapter $cachePool;

    public function __construct()
    {
        $this->cachePool = new FilesystemAdapter('routes', 0, 'cache');
    }

    public function save(array $routes): void
    {
        $data = $this->cachePool->getItem('routes');

        if (!$data->isHit()) {
            $data->set($routes);
            $this->cachePool->save($data);
        }
    }

    /**
     * @return Route[][]
     * @throws InvalidArgumentException
     */
    public function get(): array
    {
        if ($this->cachePool->hasItem('routes')) {
            $data = $this->cachePool->getItem('routes');

            return $data->get();
        }

        return [];
    }
}
