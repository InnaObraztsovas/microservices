<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class APIController extends AbstractController
{
    #[Route(path: '/register', name: 'register_service', methods: 'POST')]
    public function register(Request $request)
    {
        $routes = json_decode($request->getContent(), flags: JSON_THROW_ON_ERROR);

        //save routes

        return $this->json(null);
    }

    #[Route(path: '/', name: 'entrypoint')]
    public function handle()
    {
        //check if you can match request to one of saved route and forward call
    }

}
