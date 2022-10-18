<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order', methods: 'GET')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $orders = $entityManager->getRepository(Order::class);

        $query = $orders->createQueryBuilder('o')
            ->orderBy('o.id', 'DESC')
            ->getQuery();

        $page = $request->get('page', '');

        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);

        if (!empty($page)) {
            $pageSize = 10;
            $paginator->getQuery()
                ->setFirstResult($pageSize * ($page - 1))
                ->setMaxResults($pageSize);
        } else {
            $paginator->getIterator();
        }

        foreach ($paginator as $order) {
            $data[] = [
                'id' => $order->getId(),
                'created_at' => $order->getCreatedAt(),
                'amount' => $order->getAmount(),
                'user_id' => $order->getUserId()
            ];
        }
        return $this->json($data, 200);
    }

    #[Route('/users/{id}/orders', name: 'order_store', methods: 'POST')]
    public function createOrder(EntityManagerInterface $entityManager, Request $request): Response
    {
        $data = json_decode($request->getContent(), true, flags: JSON_THROW_ON_ERROR);

        $order = new Order();
        $order->setAmount($data['amount']);
        $order->setUserId($data['user_id']);
        $order->setCreatedAt(new \DateTimeImmutable());
        $entityManager->persist($order);
        $entityManager->flush();

        return $this->json('The new order is created', 201);

    }

    #[Route('/users/{id}/orders', name: 'order_show', methods: 'GET')]
    public function getById(EntityManagerInterface $entityManager, int $id): Response
    {
        $repository = $entityManager->getRepository(Order::class);
        $order = $repository->find($id);
        if (!$order) {
            return $this->json('No order found for id' . $id, 404);
        }
        return $this->json($order, 200);
    }

}
