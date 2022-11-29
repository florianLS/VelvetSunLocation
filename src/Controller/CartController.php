<?php

namespace App\Controller;

use App\Classe\Cart;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/commande', name: 'cart')]
    public function index(Cart $cart): Response
    {
        $totalProductsQuantity = $cart->getCurrentFullQuantity();
        $cart = $cart->getFull();

        return $this->render('cart/index.html.twig', [
            'cart' => $totalProductsQuantity,
            'fullCart' => $cart
        ]);
    }

    #[Route('/commande/add/{id}', name: 'add_to_cart', methods: ['POST'])]
    public function add($id, Request $request, Cart $cart): Response
    {
        if (empty($id)) {
            $id = $request->request->get("id");
        }
        $quantity = $request->request->get("quantity");
        $setExactQuantity = $request->request->get("setExactQuantity");
        if ($quantity != null && $quantity > 0) {
            $cart->add($id, intval($quantity));
        } else if ($quantity != null && $quantity < 0) {
            $cart->remove($id, intval($quantity));
        } else {
            $cart->setExactQuantity($id, $setExactQuantity);
        }
        $totalProductsQuantity = $cart->getCurrentFullQuantity();
        return new JsonResponse($totalProductsQuantity);
    }

    #[Route('/commande/delete/{id}', name: 'delete_product_to_cart')]
    public function deleteProduct($id, Cart $cart): Response
    {
        if (!empty($id)) {
            $cart->deleteProduct($id);
        }

        return $this->redirectToRoute('cart');
    }

    #[Route('/commande/clear', name: 'delete_cart')]
    public function clear(Cart $cart): Response
    {
        $cart->clear();

        return $this->redirectToRoute('cart');
    }
}
