<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order',methods: 'GET')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $orders = $doctrine->getManager()
            ->getRepository(Order::class)
            ->findAll();

        $data = [];

        foreach ($orders as $order) {
            $data[] = [
                'id' => $order->getId(),
                'created_at' => $order->getCreatedAt(),
                'amount' => $order->getAmount(),
                'user_id' => $order->getUserId()
            ];
        }
        return $this->json($data);
    }

    #[Route('/users/{id}/orders', name: 'order_store', methods:'POST')]
    public function createOrder (ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();

        $order = new Order();
        $order->setAmount($request->request->get('amount'));
        $order->setUserId($request->request->get('user_id'));

        $entityManager->persist($order);

        $entityManager->flush();

        return $this->json('The new order is created',  201);

    }

    #[Route('/users/{id}/orders', name: 'order_show', methods:'GET')]
    public function getById (ManagerRegistry $doctrine, int $id): Response
    {
        $repository = $doctrine->getRepository(Order::class);
        $order = $repository->find($id);
        if (!$order) {
            return $this->json('No order found for id' . $id, 404);
        }
        return $this->json($order);
    }

}
