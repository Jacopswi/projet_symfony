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
use App\Entity\Category;
use App\Repository\CategoryRepository;


class ProductController extends AbstractController
{
    #[Route('/product', name: 'product_liste')]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository, Request $request): Response
    {
        $categories = $categoryRepository->findAll();
        $categoryId = $request->query->get('category');

        $products = $productRepository->findAll();
        return $this->render('product/index.html.twig', [
            'products' => $products,
            'categories' => $categories
        ]);

        dump($products);

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
        return $this->redirectToRoute('product_liste');  
    }

    return $this->render('product/results.html.twig', [
        'products' => $products,
        'query' => $query    ]);
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
        $entityManager->remove($product);
        $entityManager->flush();

    return $this->redirectToRoute('admin_index');
}

#[Route('/category/{id}', name: 'product_by_category')]
public function showByCategory(
    Category $category, 
    ProductRepository $productRepository
): Response {
    $products = $productRepository->findByCategory($category);

    return $this->render('product/category.html.twig', [
        'category' => $category,
        'products' => $products,
    ]);
}

}
