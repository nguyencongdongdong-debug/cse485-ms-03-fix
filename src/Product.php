<?php

class Product
{
    private string $sku;
    private string $name;
    private int $categoryId;
    private int $price;
    private int $qty;

    public function __construct(
        string $sku,
        string $name,
        int $categoryId,
        int $price,
        int $qty
    ) {
        $this->sku = $sku;
        $this->name = $name;
        $this->categoryId = $categoryId;
        $this->price = $price;
        $this->qty = $qty;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getQty(): int
    {
        return $this->qty;
    }

    public function lineTotal(): int
    {
        return $this->price * $this->qty;
    }

    public function stockLevel(): string
    {
        if ($this->qty >= 5) {
            return 'Du';
        }

        if ($this->qty >= 2) {
            return 'Sap het';
        }

        return 'Can nhap';
    }

    public function toArray(): array
    {
        return [
            'sku' => $this->sku,
            'name' => $this->name,
            'category_id' => $this->categoryId,
            'price' => $this->price,
            'qty' => $this->qty,
            'line_total' => $this->lineTotal(),
            'stock_level' => $this->stockLevel(),
        ];
    }
}
