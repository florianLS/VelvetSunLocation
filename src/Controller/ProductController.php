<?php

namespace App\Controller;


use App\Classe\Cart;
use App\Entity\Product;
use App\Entity\StockProduct;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/produit/{id}', name: 'product')]
    public function index($id, Cart $cart): Response
    {
        $product = $this->em->getRepository(Product::class)->findOneById($id);
        $stockProduct = $this->em->getRepository(StockProduct::class)->findOneByProduct($id);
        $stock = $stockProduct->getStockNumber();

        $totalProductsQuantity = $cart->getCurrentFullQuantity();

        return $this->render('product/index.html.twig', [
            'product' => $product,
            'stock' => $stock,
            'cart' => $totalProductsQuantity
        ]);
    }
}
