<?php

namespace App\Controller;

use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user', methods: 'GET')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $users = $doctrine->getManager()
            ->getRepository(User::class)
            ->findAll();

        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
            ];
        }
        return $this->json($data);
    }

    #[Route('/user', name: 'user_store', methods:'POST')]
    public function createUser (ManagerRegistry $doctrine, Request $request)
    {
        $entityManager = $doctrine->getManager();

        $user = new User();
        $user->setEmail($request->request->get('email'));

        $entityManager->persist($user);

        $entityManager->flush();

//        return ?

    }

    #[Route('/user/{id}', name: 'user_show', methods:'GET')]
    public function getById (ManagerRegistry $doctrine, int $id): Response
    {
        $repository = $doctrine->getRepository(User::class);
        $user = $repository->find($id);
        return $this->json($user);
    }

}
