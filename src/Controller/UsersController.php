<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersController extends AbstractController
{
    #[Route('/api/users', name: 'app_users', methods:['GET']),]
    public function GetUsers(UserRepository $userRepository): Response
    {
        $data = $userRepository->findAll();
        return $this->json($data);
    }
    
    #[Route('/api/users/{id}', name: 'find_user', methods:['GET']),]
    public function FindUser(UserRepository $userRepository, $id): Response
    {
        $data = $userRepository->find($id);
        return $this->json($data);
    }

    #[Route('/api/user', name: 'new_user', methods:['POST'], format:'json'),]
    public function NewUser(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator, UserPasswordHasherInterface $passwordHasher): Response
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $user->setName($data['Name']);
        $user->setLastName($data['LastName']);
        $user->setAge($data['Age']);
        $user->setUserName($data['UserName']);
        $user->setPassword($passwordHasher->hashPassword($user, $data['Password']));
        $user->setRoles([]);
        $errors = $validator->validate($user);
        if (count(value: $errors) > 0) {
            return new Response((string) $errors, 400);
        }
        $entityManager->persist($user);
        $entityManager->flush();

        return new Response('Saved new user with id '.$user->getId());
    }

    #[Route('/api/users/{id}', name: 'delete_user', methods:['DELETE']),]
    public function deleteUser(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, $id): Response
    {
        $data = $userRepository->find($id);
        $entityManager->remove($data);
        $entityManager->flush();
        return new Response('Deleted user with id '.$id);
    }

    #[Route('/api/users/edit/{id}', name: 'edit_user', methods:['PUT']),]
    public function editUser(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, $id, ValidatorInterface $validator, UserPasswordHasherInterface $passwordHasher): Response
    {
        $data = json_decode($request->getContent(), true);
        $user = $userRepository->find($id);
        if (isset($data['Name'])) $user->setName($data['Name']);
        if (isset($data['LastName'])) $user->setLastName($data['LastName']);
        if (isset($data['Age'])) $user->setAge($data['Age']);
        if (isset($data['UserName']))$user->setUserName($data['UserName']);
        $user->setPassword($passwordHasher->hashPassword($user, $data['Password']));
        $errors = $validator->validate($user);
        if (count(value: $errors) > 0) {
            return new Response((string) $errors, 400);
        }
        $entityManager->flush();
        return new Response('Updated user with id '.$id);
    }
}
