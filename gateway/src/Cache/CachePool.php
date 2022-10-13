<?php
namespace App\Cache;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class CachePool
{
    private FilesystemAdapter $cachePool;

    public function __construct()
    {
        $this->cachePool = new FilesystemAdapter('', 0, 'cache');
    }

    public function save(string $key, array $response): void
    {
        $data = $this->cachePool->getItem($key);
        if (!$data->isHit()) {
            $data->set(json_encode($response));
            $this->cachePool->save($data);

        }
    }

    public function has(string $key): string
    {
        if ($this->cachePool->hasItem($key)) {
            $data = $this->cachePool->getItem($key);
        }
        return $data->get();
    }

    public function existCache(string $key): bool
    {
        return $this->cachePool->hasItem($key);
    }

}