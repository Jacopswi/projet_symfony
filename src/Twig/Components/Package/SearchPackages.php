<?php

// src/Twig/Components/ProductSearch.php
namespace App\Twig\Components\Package;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use App\Repository\ProductRepository;


#[AsLiveComponent]
class SearchPackages
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $query = '';

    public function __construct(private ProductRepository $productRepository)
    {
    }

    public function getProducts(): array
    {
        // example method that returns an array of Products
        return $this->productRepository->search($this->query);
    }
}
