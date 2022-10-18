<?php

namespace App\Controller;

use App\Cache\CachePool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class APIController extends AbstractController
{

    public function __construct(public CachePool $cachePool)
    {
        $this->client = HttpClient::create();
    }

    #[Route(path: '/register', name: 'register_service', methods: 'POST')]
    public function register(Request $request)
    {
        $data = json_decode($request->getContent(), flags: JSON_THROW_ON_ERROR);
        $cache = md5(date('Y-m-d'));
        if ($this->cachePool->existCache($cache)) {
            $msg = 'Rebuild';
            $cacheData = $this->cachePool->get($cache);
            $newRoutes = $this->transform($data, json_decode($cacheData, true));
            $this->cachePool->delate($cache);
            $this->cachePool->save($cache, $newRoutes);
        } else {
            $msg = 'Successfully stored to cache';
            $this->cachePool->save($cache, $this->transform($data));
        }
        return $this->json(['msg' => $msg], 201);
    }

    private function transform(object $data, array $cache = []): array
    {
        $routes = $cache;
        foreach ($data->routes as $route) {
            if (!empty($route->methods)) {
                foreach ($route->methods as $method) {
                    if (empty($routes[$method])) {
                        $routes[$method] = [$route->path => $data->service_name];
                    } else {
                        $routes[$method] += [$route->path => $data->service_name];
                    }
                }
            }
        }

        return $routes;
    }


    #[Route(path: '/{slug}', name: 'entrypoint', requirements: ['slug' => '.*'])]
    public function handle(Request $request): Response
    {

        $options = [
            'headers' => [
                'Content-Type: application/json',
            ],
            'body' => $request->getContent()
        ];
        $cacheData = json_decode($this->cachePool->get(md5(date('Y-m-d'))), true);
        $url = preg_replace('/\d+/', '{id}', $request->getPathInfo());
        try {
            $service = $cacheData[$request->getMethod()][$url];
        } catch (\Throwable) {
            return new Response('Enter the correct service', 404);
        }
        $response = $this->client->request($request->getMethod(), "http://{$service}{$request->getRequestUri()}", $options);
        return new Response($response->getContent());
    }
}
