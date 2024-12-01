<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route(path: '/{_locale}/',
    name: 'home',
    requirements: [
        '_locale' => 'en|fr',
    ],)]
    public function index(): Response
    {
        return $this->render('home/home.html.twig');
    }

    #[Route(path: '/',
    name: 'home2',)]
    public function index2(): Response
    {
        return $this->render('home/home.html.twig');
    }
}