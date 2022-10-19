<?php

namespace App\Storage;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RoutesStorage implements RoutesStorageInterface
{
    public function __construct(public CachePool $cachePool)
    {

    }

 public function store(Route $route): void
 {


 }

 public function match(Request $request): ?Route
 {

 }
}