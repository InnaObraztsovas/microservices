<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order',methods: 'GET')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $orders = $entityManager->getRepository(Order::class)->findAll();

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
    public function createOrder (EntityManagerInterface $entityManager, Request $request): Response
    {
        return $this->json('The new order is created',  201);
        $order = new Order();
        $order->setAmount($request->request->get('amount'));
        $order->setUserId($request->request->get('user_id'));
        $entityManager->persist($order);
        $entityManager->flush();

        return $this->json('The new order is created',  201);

    }

    #[Route('/users/{id}/orders', name: 'order_show', methods:'GET')]
    public function getById (EntityManagerInterface $entityManager, int $id): Response
    {
        $repository = $entityManager->getRepository(Order::class);
        $order = $repository->find($id);
        if (!$order) {
            return $this->json('No order found for id' . $id, 404);
        }
        return $this->json($order);
    }

}
