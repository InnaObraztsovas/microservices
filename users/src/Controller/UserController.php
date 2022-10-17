<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
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
        return $this->json($data);
    }

    #[Route('/user', name: 'store', methods:'POST')]
    public function createUser (EntityManagerInterface $entityManager, Request $request): Response
    {
//        $data = json_decode($request->getContent(), true, flags: JSON_THROW_ON_ERROR);
//        dd($data);
        $user = new User();
//        $user->setEmail('one@email.com');
        $user->setEmail($request->get('email'));
//        $user->setEmail(json_decode($request->get('email'), true, flags: JSON_THROW_ON_ERROR));
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
//            var_dump($user);die();
            return $this->json('No user found for id' . $id, 404);
        }
        return $this->json('Ok');
    }

}
