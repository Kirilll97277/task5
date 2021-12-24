<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserListType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {

        $entityManager->getEventManager();
        $users = $entityManager->getRepository(User::class)->findAll();

        $form = $this->createForm(UserListType::class, null, ['userList' => $users]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }
        return $this->render('user/index.html.twig', array(
            'form' => $form->createView(),
            'users' => $users,
        ));
    }

    #[Route('user/delete', name: 'user_delete')]
    public function deleteUsers(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $ids = json_decode($request->getContent(), true)['ids'];
        $doctrine = $entityManager;
        $users = $doctrine->getRepository(User::class)->findBy(['id' => $ids]);
        dump($users);
        try{
            foreach ($users as $user) {
                $doctrine->remove($user);
            }
            $doctrine->flush();

            return new JsonResponse(json_encode(['success' => true]));

        } catch (\Exception $exception)
        {
            return new JsonResponse(json_encode(['success' => false]));
        }
    }

    #[Route('user/block', name: 'user_block')]
    public function blockUsers(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $ids = json_decode($request->getContent(), true)['ids'];
        $doctrine = $entityManager;
        $users = $doctrine->getRepository(User::class)->findBy(['id' => $ids]);

        try{
            foreach ($users as $user) {
                $user->setIsActive(false);
            }
            $doctrine->flush();

            return new JsonResponse(json_encode(['success' => true]));

        } catch (\Exception $exception)
        {
            return new JsonResponse(json_encode(['success' => false]));
        }
    }

    #[Route('user/unblock', name: 'user_unblock')]
    public function unblockUsers(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $ids = json_decode($request->getContent(), true)['ids'];
        $doctrine = $entityManager;
        $users = $doctrine->getRepository(User::class)->findBy(['id' => $ids]);

        try{
            foreach ($users as $user) {
                $user->setIsActive(true);
            }
            $doctrine->flush();

            return new JsonResponse(json_encode(['success' => true]));

        } catch (\Exception $exception)
        {
            return new JsonResponse(json_encode(['success' => false]));
        }
    }

}
