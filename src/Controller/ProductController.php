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
use App\Form\SearchForm;
use Knp\Component\Pager\PaginatorInterface;




class ProductController extends AbstractController
{
    #[Route('/product', name: 'product_liste')]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $categories = $categoryRepository->findAll();
        $categoryId = $request->query->get('category');

        $qb = $productRepository->createQueryBuilder('p')
        ->orderBy('p.id', 'ASC');

        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1), 
            9 
        );

        $products = $productRepository->findAll();
        return $this->render('product/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'pagination' => $pagination,
        ]);

    }

    #[Route('/rec_dynamique', name: 'product_dynamique')]
    public function recDyn(ProductRepository $productRepository, CategoryRepository $categoryRepository, Request $request): Response
    {
        $categories = $categoryRepository->findAll();
        $categoryId = $request->query->get('category');

        $products = $productRepository->findAll();
        return $this->render('product/recDynamique.html.twig', [
            'products' => $products,
            'categories' => $categories,
        ]);

    }

    #[Route('/admin/product/new', name: 'product_new')]
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
     
        return $this->render('admin/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/search', name: 'product_search', methods: ['GET'])]
public function search(Request $request, ProductRepository $productRepository): Response
{
    $query = $request->query->get('query', '');  

    $products = $productRepository->searchProducts(['name' => $query]);

    $form = $this->createForm(SearchForm::class, $products);
     
    $form->handleRequest($request);
 

    if (empty($query)) {
        return $this->redirectToRoute('product_liste');  
    }

    return $this->render('product/results.html.twig', [
        'products' => $products,
        'query' => $query    ]);
}

#[Route('/product/{id}', name: 'product_details', requirements: ['id' => '\d+'])]
    public function details(Product $product): Response
    {
        return $this->render('product/details.html.twig', [
            'product' => $product,
        ]);
    }

#[Route('/autofill', name: 'product_autofill', methods: ['GET'])]
public function autofill(Request $request, ProductRepository $productRepository): Response
{
    {
        $form = $this->createForm(SearchForm::class);

        // Traitement de la soumission, mais pas besoin de logique côté serveur pour les résultats
        $form->handleRequest($request);

        return $this->render('product/results.html.twig', [
            'form' => $form->createView(),
        ]);
    }
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
