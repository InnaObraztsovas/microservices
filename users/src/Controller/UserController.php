<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user', methods: 'GET')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $users = $entityManager->getRepository(User::class);

        $query = $users->createQueryBuilder('u')
            ->orderBy('u.id', 'DESC')
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

        foreach ($paginator as $user) {
            $data[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
            ];
        }

        return $this->json('List of users with pagination (10 items per page)');
    }

    #[Route('/user', name: 'store', methods: 'POST')]
    public function createUser(EntityManagerInterface $entityManager, Request $request): Response
    {
        $data = json_decode($request->getContent(), true, flags: JSON_THROW_ON_ERROR);
        $user = new User();
        $user->setEmail($data['email']);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json('The new user is created', 201);

    }

    #[Route('/user/{id}', name: 'show', methods: 'GET')]
    public function getById(EntityManagerInterface $entityManager, int $id): Response
    {
        $repository = $entityManager->getRepository(User::class);
        $user = $repository->find($id);
        if (!$user) {
            return $this->json('No user found for id' . $id, 404);
        }
        return $this->json('Ok');
    }

}
