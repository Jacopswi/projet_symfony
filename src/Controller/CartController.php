<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Enum\OrderStatus;



class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_index')]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        $cart = $session->get('cart', []);

        $cartWithData = [];
        foreach ($cart as $id => $quantity) {
            $product = $productRepository->find($id);
            if ($product) {
                $cartWithData[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                ];
            }
        }

        $total = 0;
        foreach ($cartWithData as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }

        return $this->render('cart/index.html.twig', [
            'items' => $cartWithData,
            'total' => $total,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add', methods: ['POST'])]
public function add($id, Request $request, SessionInterface $session, ProductRepository $productRepository): Response
{
    $product = $productRepository->find($id);

    if (!$product) {
        throw $this->createNotFoundException('Produit non trouvé.');
    }

    $stock = $product->getStock();

    $cart = $session->get('cart', []);

    $quantityRequested = (int) $request->request->get('quantity', 1);

    $currentQuantityInCart = $cart[$id] ?? 0;

    $newQuantity = $currentQuantityInCart + $quantityRequested;

    if ($newQuantity > $stock) {
        $this->addFlash(
            'error',
            'La quantité demandée dépasse le stock disponible. Stock maximum : ' . $stock
        );
        return $this->redirectToRoute('product_liste'); 
    }

    $cart[$id] = $newQuantity;
    $session->set('cart', $cart);

    $this->addFlash('success', 'Le produit a été ajouté au panier.');

    return $this->redirectToRoute('cart_index'); 
}


    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove($id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/valider', name: 'checkout', methods: ['POST'])]
    public function checkout(SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $cart = $session->get('cart', []);
        if (!$this->getUser()) {
            $this->addFlash('danger', 'Vous devez être connecté pour valider une commande.');
            return $this->redirectToRoute('app_login');
        }

        if (empty($cart)) {
            $this->addFlash('danger', 'Votre panier est vide.');
            return $this->redirectToRoute('cart');
        }

        $order = new Order();
        $order->setUtilisateur($this->getUser());
        $order->setStatus(OrderStatus::EnPreparation);
        $order->setCreatedAt(new \DateTimeImmutable());

        $orderCount = $entityManager->getRepository(Order::class)->count([]);
        $reference = sprintf('ORD%04d', $orderCount + 1);
        $order->setReference($reference);

        $entityManager->persist($order);

        foreach ($cart as $id => $quantity) {
            $product = $entityManager->getRepository(Product::class)->find($id);
            if ($product) {
                $product->setStock($product->getStock() - $quantity);

                $orderItem = new OrderItem();
                $orderItem->setOrder($order);
                $orderItem->setProduct($product);
                $orderItem->setQuantity($quantity);
                $orderItem->setProductPrice($product->getPrice());
                $entityManager->persist($orderItem);
            }
        }

        $entityManager->flush();
        $session->remove('cart');

        $this->addFlash('success', 'Commande validée avec succès.');
        return $this->redirectToRoute('product_liste');
    }
}
