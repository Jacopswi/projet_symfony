<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\User;
use App\Repository\UserRepository;


class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin_page")
     * @IsGranted("ROLE_ADMIN")
     */

    #[Route('/admin', name: 'admin_index')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);

        dump($users); 

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);

        
    }

    
}
