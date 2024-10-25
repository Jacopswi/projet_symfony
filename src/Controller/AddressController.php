<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Address;
use App\Repository\AddressRepository;

class AddressController extends AbstractController
{
    #[Route('/addresses', name: 'address_index')]
    public function index(AddressRepository $addressRepository): Response
    {
        $addresses = $addressRepository->findAll();
        return $this->render('address/index.html.twig', [
            'addresses' => $addresses,
        ]);

        dump($addresses); // This will print the content in the Symfony Profiler

        return $this->render('address/index.html.twig', [
            'addresses' => $addresses,
        ]);
    }
}
