<?php

namespace App\Entity;

use App\Repository\StockProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockProductRepository::class)]
class StockProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Product $product = null;

    #[ORM\Column]
    private ?int $stock_number = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getStockNumber(): ?int
    {
        return $this->stock_number;
    }

    public function setStockNumber(int $stock_number): self
    {
        $this->stock_number = $stock_number;

        return $this;
    }
}
