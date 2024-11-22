<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\ProductType;
use App\Entity\Product;
use App\Repository\ProductRepository;


class ProductController extends AbstractController
{
    #[Route('/product', name: 'product_liste')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);

        dump($products); // This will print the content in the Symfony Profiler

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/new', name: 'product_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
     
        $form->handleRequest($request);
     
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();
    
            $this->addFlash('success', 'Produit créé!');
     
            return $this->redirectToRoute('admin_index');
        }
     
        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/search', name: 'product_search', methods: ['GET'])]
    public function search(Request $request, ProductRepository $productRepository): Response
    {
        $query = $request->query->get('query', '');

        $products = $productRepository->searchProducts(['name' => $query]);

        if (empty($query)) {
            return $this->redirectToRoute('home');
        }

        return $this->render('product/results.html.twig', [
            'products' => $products,
            'query' => $query,
        ]);
    }

    #[Route('/admin/product/edit/{id}', name: 'product_edit')]
    public function edit(
        Product $product, 
        Request $request, 
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le produit a été modifié avec succès.');

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/product_edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }

    #[Route('/admin/product/delete/{id}', name: 'product_delete', methods: ['POST'])]
public function delete(
    Product $product, 
    Request $request, 
    EntityManagerInterface $entityManager
): Response {
    if ($this->isCsrfTokenValid('delete_product_' . $product->getId(), $request->request->get('_token'))) {
        $entityManager->remove($product);
        $entityManager->flush();

        $this->addFlash('success', 'Le produit a été supprimé avec succès.');
    } else {
        $this->addFlash('danger', 'Échec de la validation du token CSRF.');
    }

    return $this->redirectToRoute('admin_index');
}




}
