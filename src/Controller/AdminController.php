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
use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Repository\OrderRepository;
use App\Entity\Order;


class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin_page")
     * @IsGranted("ROLE_ADMIN")
     */

     #[Route('/admin', name: 'admin_index')]
     public function index(UserRepository $userRepository, ProductRepository $productRepository, OrderRepository $orderRepository): Response
     {
         $users = $userRepository->findAll();
         $products = $productRepository->findAll();
         $orders = $orderRepository->findAll();
     
         dump($users, $products);
     
         return $this->render('admin/index.html.twig', [
             'users' => $users,
             'products' => $products,
             'orders' => $orders,
         ]);
     }
     

    
}
