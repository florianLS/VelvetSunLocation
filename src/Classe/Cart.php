<?php

namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    private $em;
    private $session;

    public function __construct(EntityManagerInterface $em, RequestStack $session)
    {
        $this->em = $em;
        $this->session = $session->getSession();
    }

    public function add($id, $quantity)
    {
        $cart = $this->session->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id] += $quantity;
        } else {
            $cart[$id] = $quantity;
        }

        $this->session->set('cart', $cart);
        return;
    }

    public function remove($id, $quantity)
    {
        $cart = $this->session->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id] += $quantity;
        }

        $this->session->set('cart', $cart);
        return;
    }

    public function setExactQuantity($id, $quantity)
    {
        $cart = $this->session->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id] = $quantity;
        }

        $this->session->set('cart', $cart);
        return;
    }

    public function deleteProduct($id)
    {
        $cart = $this->session->get('cart', []);
        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }

        $this->session->set('cart', $cart);
        return;
    }

    public function get()
    {
        return $this->session->get('cart', []);
    }

    public function clear()
    {
        return $this->session->clear();
    }

    public function getCurrentFullQuantity()
    {
        $cart = $this->session->get('cart', []);

        $totalProductsQuantity = 0;
        foreach ($cart as $id => $quantity) {
            $totalProductsQuantity += $quantity;
        }

        return $totalProductsQuantity;
    }

    public function getFull()
    {
        $cartComplete = [];
        if ($this->get()) {
            foreach ($this->get() as $id => $quantity) {
                $product_object = $this->em->getRepository(Product::class)->findOneById($id);
                if (!$product_object) {
                    $this->deleteProduct($id);
                    continue;
                }
                $cartComplete[] = [
                    'product' => $product_object,
                    'quantity' => $quantity
                ];
            }
        }

        return $cartComplete;
    }
}
