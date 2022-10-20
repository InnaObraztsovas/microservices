<?php

namespace App\Controller;

use App\Storage\RoutesStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class APIController extends AbstractController
{
    public function __construct(private RoutesStorage $routesStorage)
    {
        $this->client = HttpClient::create();
    }

    #[Route(path: '/register', name: 'register_service', methods: 'POST')]
    public function register(Request $request)
    {
        $data = json_decode($request->getContent(), true, flags: JSON_THROW_ON_ERROR);

        $routes = [];

        foreach ($data['routes'] as $route) {
            $routes[] = new \Symfony\Component\Routing\Route(
                path: $route['path'],
                defaults: $route['defaults'],
                host: $data['service_name'],
                methods: $route['methods'],
            );
        }

        if ($routes) {
            $this->routesStorage->store($data['service_name'], ...$routes);
        }

        return $this->json(null, 201);
    }


    #[Route(path: '/{slug}', name: 'entrypoint', requirements: ['slug' => '.*'])]
    public function handle(Request $request): Response
    {
        try {
            $route = $this->routesStorage->match($request);
        } catch (\Throwable) {
            return $this->json(['error' => 'Route not found'], Response::HTTP_NOT_FOUND);
        }

        $response = $this->client->request(
            $request->getMethod(),
            "http://{$route->getHost()}{$request->getRequestUri()}",
            [
                'headers' => $request->headers->all(),
                'body' => $request->getContent()
            ]
        );
        return new Response($response->getContent(), $response->getStatusCode(), $response->getHeaders());
    }
}
