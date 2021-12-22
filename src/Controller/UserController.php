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

    #[Route('user/block', name: 'user_block')]
    public function blockUsers(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        //$entityManager->remove();
        $ids = json_decode($request->getContent(), true)['ids'];
        $doctrine = $this->$entityManager;
        $users = $doctrine->getRepository(User::class)->getUsersByIds($ids);
        try {
//            foreach ($users as $user) {
//                $doctrine->getManager()->remove($user);
//            }
//            $doctrine->getManager()->flush();
            return new JsonResponse(json_encode(['success' => true]));
            }
        catch
        (\Exception $exception)
        {
            return new JsonResponse(json_encode(['success' => false]));
        }
    }

}
