<?php

namespace App\Controller;


use App\Classe\Cart;
use App\Classe\Search;
use App\Entity\Product;
use App\Entity\StockProduct;
use App\Form\SearchProductsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/produits', name: 'products')]
    public function index(Cart $cart, Request $request): Response
    {
        $products = $this->em->getRepository(Product::class)->findAll();
        $totalProductsQuantity = $cart->getCurrentFullQuantity();

        $search = new Search();
        $form = $this->createForm(SearchProductsType::class, $search);

        $stock = new StockProduct();
        dd($stock->getProduct());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $products = $this->em->getRepository(Product::class)->findWithSearch($search);
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
            'cart' => $totalProductsQuantity
        ]);
    }

    #[Route('/produit/{id}', name: 'product')]
    public function selected($id, Cart $cart): Response
    {
        $product = $this->em->getRepository(Product::class)->findOneById($id);
        if ($product) {
            $stockProduct = $this->em->getRepository(StockProduct::class)->findOneByProduct($id);
            $stock = $stockProduct->getStockNumber();

            $totalProductsQuantity = $cart->getCurrentFullQuantity();

            return $this->render('product/product.html.twig', [
                'product' => $product,
                'stock' => $stock,
                'cart' => $totalProductsQuantity
            ]);
        } else {
            return $this->redirectToRoute('home');
        }
    }
}
