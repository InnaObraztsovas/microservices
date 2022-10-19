<?php

namespace App\Storage;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

interface RoutesStorageInterface
{
    public function store(Route $route);

    public function match(Request $request): ?Route;
}