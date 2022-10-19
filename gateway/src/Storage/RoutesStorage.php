<?php

namespace App\Storage;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RoutesStorage
{
    public function __construct(private CachePool $cachePool)
    {
    }

    public function store(string $serviceName, Route ...$routes): void
    {
        $existingRoutes = $this->cachePool->get();

        $existingRoutes[$serviceName] = [];

        foreach ($routes as $route) {
            $existingRoutes[$serviceName][$this->buildRouteName($route)] = $route;
        }

        $this->cachePool->save($existingRoutes);
    }

    public function match(Request $request): Route
    {
        $hosts = [];

        $collection = new RouteCollection();

        foreach ($this->cachePool->get() as $routes) {
            foreach ($routes as $name => $route) {
                $hosts[$name] = $route->getHost();

                $route->setHost(null);

                $collection->add($name, $route);
            }
        }

        $match = (new UrlMatcher($collection, new RequestContext()))->matchRequest($request);

        if ($match) {
            $route = $collection->get($match['_route']);
            $route->setHost($hosts[$match['_route']]);

            return $route;
        }

        throw new RouteNotFoundException();
    }

    private function buildRouteName(Route $route): string
    {
        return md5(serialize($route));
    }
}
