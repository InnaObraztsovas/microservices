<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\NoPrivateNetworkHttpClient;

class Controller extends AbstractController
{
//    #[Route('/a/p/i', name: 'app_a_p_i')]
//    public function index(): Response
//    {
//        return $this->render('api/index.html.twig', [
//            'controller_name' => 'APIController',
//        ]);
//    }
public function index()
{
    $client = new NoPrivateNetworkHttpClient(HttpClient::create());
    $client->request('GET', 'http://localhost/');
}
}
