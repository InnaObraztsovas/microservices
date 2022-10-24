<?php
namespace App\Storage;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Routing\Route;

class CachePool
{

    public function __construct(private AdapterInterface $cachePool)
    {
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

    public  function delete()
    {
        return  $this->cachePool->clear('routes');
    }
}