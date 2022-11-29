<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'home')]
    public function index(Cart $cart): Response
    {
        $favoriteProducts = $this->em->getRepository(Product::class)->findByFavorite(true);
        $totalProductsQuantity = $cart->getCurrentFullQuantity();

        return $this->render('home/index.html.twig', [
            'favoriteProducts' => $favoriteProducts,
            'cart' => $totalProductsQuantity
        ]);
    }
}
