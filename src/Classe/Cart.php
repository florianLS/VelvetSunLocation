<?php

namespace App\Classe;

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

    public function get()
    {
        return $this->session->get('cart', []);
    }

    public function delete()
    {
        return $this->session->clear();
    }

    public function getCurrentFullQuantity()
    {
        $cart = $this->session->get('cart', []);

        return $cart;
    }
}
