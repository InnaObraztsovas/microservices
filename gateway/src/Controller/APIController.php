<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class APIController extends AbstractController
{
    #[Route(path: '/register', name: "register_service", methods: "POST")]
    public function register(Request $request)
    {
        //save routes

        return new JsonResponse(['data'], 200, ["Content-Type" => "application/json"]);
    }

    #[Route(path: '/', name: "entrypoint")]
    public function handle()
    {
        //check if you can match request to saved route and forward call
    }

}
