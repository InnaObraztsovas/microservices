<?php

namespace App\Controller;

use App\Cache\CachePool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class APIController extends AbstractController
{

    public function __construct(public CachePool $cachePool)
    {
    }

    #[Route(path: '/register', name: 'register_service', methods: 'POST')]
    public function register(Request $request)
    {
        $data = json_decode($request->getContent(), flags: JSON_THROW_ON_ERROR);
        $routes = $this->transform($data);
        $cache = md5($data->service_name);
        if ($this->cachePool->existCache($cache)) {
            $msg = 'Already exist';
           $this->cachePool->has($cache);
        } else {
            $msg = 'Successfully stored to cache';
            $this->cachePool->save($cache, (array)$data->routes);
        }
        return $this->json(['msg' => $msg], 201);
    }

    private function transform(object $data): array
    {
        $routes = [];
        foreach ($data->routes as $route) {
            if (!empty($route->methods)) {
                foreach ($route->methods as $method) {
                    $routes[$method] = [
                        $route->path => $data->service_name
                    ];
                }
            }
        }
        return $routes;
    }


    #[Route(path: '/{slug}', name: 'entrypoint', requirements: ['slug' => '.*'])]
    public function handle(Request $request)
    {
      $uri = json_encode($request->getRequestUri());
//
//     if ($this->cachePool->has($uri)) {
//         $this->redirectToRoute($uri);
//     }
      dd($uri);
        //check if you can match request to one of saved route and forward call
    }

}
