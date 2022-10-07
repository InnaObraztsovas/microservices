<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\NoPrivateNetworkHttpClient;

class APIController extends AbstractController
{
    private \Symfony\Contracts\HttpClient\HttpClientInterface $client;

    public function __construct()
    {
        $this->client = HttpClient::create();
    }
//    #[Route('/a/p/i', name: 'app_a_p_i')]
//    public function index(): Response
//    {
//        return $this->render('api/index.html.twig', [
//            'controller_name' => 'APIController',
//        ]);
//    }
public function index()
{
//    $client = HttpClient::create();
    $response = $this->client->request('GET', 'http://users/user');
    $statusCode = $response->getStatusCode();
    $content = $response->getContent();
//    dd($content);
    dd($statusCode);
}

}
