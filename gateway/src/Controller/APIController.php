<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpClient\HttpClient;

class APIController extends AbstractController
{
//    private \Symfony\Contracts\HttpClient\HttpClientInterface $client;
//
//    public function __construct()
//    {
//        $this->client = HttpClient::create();
//    }

public function index(Request $request)
{
//$a = $request->getContent();
return json_encode($request->getUri());
dd($request);
//    $response = $this->client->request('GET', 'http://localhost');
////    $statusCode = $response->getStatusCode();
//    $content = $response->getContent();
//    dd($content);
//    dd($statusCode);
}

    #[Route(path: "/", name: "all", methods: ["POST"])]
    public function test(Request $request)
    {
        return new JsonResponse(['data'], 200, ["Content-Type" => "application/json"]);

    }

}
