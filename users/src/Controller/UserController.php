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
    public function index(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
            ];
        }
        return $this->json('Type user id');
    }

    #[Route('/user', name: 'store', methods:'POST')]
    public function createUser (EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = new User();
        $user->setEmail($request->get('email'));
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json('The new user is created',  201);

    }

    #[Route('/user/{id}', name: 'show', methods:'GET')]
    public function getById (EntityManagerInterface $entityManager, int $id): Response
    {
        $repository = $entityManager->getRepository(User::class);
        $user = $repository->find($id);
        if (!$user) {
            return $this->json('No user found for id' . $id, 404);
        }
        return $this->json('Ok');
    }

}
