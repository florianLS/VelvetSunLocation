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
    public function index(): Response
    {
        return $this->render('cart/index.html.twig');
    }

    #[Route('/commande/{id}', name: 'add_to_cart', methods: ['POST'])]
    public function add($id, Request $request, Cart $cart): Response
    {
        $quantity = $request->request->get("quantity");
        $cart->add($id, intval($quantity));

        return new JsonResponse($cart);
    }

    #[Route('/commande/delete', name: 'delete_cart')]
    public function delete(Cart $cart): Response
    {
        $cart->delete();

        return $this->render('cart/index.html.twig');
    }
}
