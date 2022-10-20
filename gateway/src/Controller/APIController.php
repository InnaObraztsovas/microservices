<?php

namespace App\Controller;

use App\Storage\CachePool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

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
            $this->cachePool->save($cache, $data->routes);
        }
        return $this->json(['msg' => $msg], 201);
    }

    private function transform(object $data, array $routes = []): array
    {
        foreach ($data->routes as $key => $route) {
            $routes[$key] = $route;
        }
        return $routes;
    }


    #[Route(path: '/{slug}', name: 'entrypoint', requirements: ['slug' => '.*'])]
    public function handle(Request $request, RouterInterface $router): Response
    {
        $options = [
            'headers' => [
                'Content-Type: application/json',
            ],
            'body' => $request->getContent()
        ];
        $cacheData = json_decode($this->cachePool->get(md5(date('Y-m-d'))), flags: JSON_THROW_ON_ERROR);
        $collection = new RouteCollection();
        foreach ($cacheData as $name => $route) {
//            $newRoute = new \Symfony\Component\Routing\Route($route->path,
//                ['_controller' => $route->defaults->_controller]);
            $newRoute = new \Symfony\Component\Routing\Route($route->path);
            $newRoute->setMethods($route->methods);
//            $newRoute->setHost($route->host);
//            var_dump($route->host);die();
//                $router->getRouteCollection()->add($name, $newRoute);
            $collection->add($name, $newRoute);
        }
//        var_dump($request->getPathInfo());die();
        $routes = $router->getRouteCollection()->all();
        $context = new RequestContext();
        $context->fromRequest($request);
        $context->setHost('users');
        $matcher = new UrlMatcher($collection, $context);
        $attributes = $matcher->match($request->getPathInfo());
//        var_dump($attributes);die();
        $generator = new UrlGenerator($collection, $context);
        $url = $generator->generate($request->getPathInfo());
        var_dump($url);die();
//        var_dump($url);die();
//        var_dump($attributes);die();
//        $routes = $router->getRouteCollection()->all();
//        var_dump($routes);die();

//
//        foreach ($routes as $rou){
//            var_dump($rou->getPath());die();
//            $response = $this->client->request($request->getMethod(), "http://{$service}{$request->getRequestUri()}", $options);
//        }
//        var_dump($routes);die();
//        var_dump($router->getContext()->getHost());die();
//        $url = preg_replace('/\d+/', '{id}', $request->getPathInfo());
//        try {
//            $service = $cacheData[$request->getMethod()][$url];
//        } catch (\Throwable) {
//            return new Response('Enter the correct service', 404);
//        }
        $response = $this->client->request($request->getMethod(), $url, $options);
        dd($response);

//        $response = $this->client->request($request->getMethod(), "http://{$service}{$request->getRequestUri()}", $options);
//        return new Response($response->getContent());
        $response = new Response();
        dd($response->getContent());
        return new Response($response->getContent());

    }


}
